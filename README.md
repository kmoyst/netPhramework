# netPhramework

`netPhramework` is a modern, object-oriented PHP framework designed for building 
highly structured, data-driven web applications. It is built from the ground up 
with a strong emphasis on clear separation of concerns, flexibility, and a 
powerful, declarative approach to application configuration.

### Resources mapped in Composite tree with linked-list navigation
The fundamental strength of the `netPhramework` design is the 
interchangeability of resources. Every resource in 
the `Application` is a `Node`. A `Node` is a component in a composite tree, 
Leaf or Composite. A Directory is a Composite, an record update process is a 
Leaf, a static Page is a Leaf, an Asset representing a database entity is
a composite of records and processes. So, `/patients/8/appointments/31/edit` is
translated into a linked list to navigate to the edit leaf.
```php
<?php

namespace netPhramework\core;

use netPhramework\exceptions\ComponentNotFound;
use netPhramework\exceptions\InvalidUri;

interface Node
{
    /**
     * @param string $name
     * @return Node
     * @throws NodeNotFound
     */
    public function getChild(string $name):Node;
    public function getName():string;  
    public function handleExchange(Exchange $exchange):void;
}

trait LeafTrait
{
    protected ?string $name = null;

    public function getChild(string $name): never
    {
        throw new ComponentNotFound("Not Found: $name");
    }

    public function getName(): string
    {
        return $this->name ?? Utils::camelToKebab(Utils::baseClassName($this));
    }
}

namespace netPhramework\core;

use netPhramework\locating\redirectors\RedirectToChild;
use netPhramework\exceptions\InvalidUri;

trait CompositeTrait
{
    /**
     * @param Exchange $exchange
     * @return void
     * @throws InvalidUri
     */
     public function handleExchange(Exchange $exchange): void
     {
        $exchange->redirect(new RedirectToChild('',$exchange->getParameters()));
     }
}
```
The key to translating the `Path` (linked list) to a `Node` from a URI is
encapsulated in `UriAdapter`:

```php
readonly class UriAdapter
{
    public function __construct(private string $uri) {}
 
    /**
     * @return MutablePath
     * @throws InvalidUri
     */
     public function getPath():MutablePath
     {
        $path = new MutablePath();
        $pattern = '|^/([^?]*)|';
        if(!preg_match($pattern, $this->uri, $matches))
			throw new InvalidUri("Invalid Uri: $this->uri");
		$this->traverseArray($path, explode('/', $matches[1]));
        return $path;
    }

    private function traverseArray(MutablePath $path, array $names):void
    {
        $path->setName($names[0]);
        if(sizeof($names) > 1)
        {
            $path->append('');
            $this->traverseArray($path->getNext(), array_slice($names, 1));
        }
    }
    
    public function getParameters():Variables
    {
        $vars = new Variables();
        $pattern = '|\?(.+)$|';
        if(preg_match($pattern, $this->uri, $matches))
        {
            parse_str($matches[1], $arr);
            $vars->merge($arr);
        }
        return $vars;
    }
}
```
Once the URI Adapter does its job, the Navigator can take over:
```php
class Navigator
{
    private Node $root;
    private Path $path;
    
    public function setRoot(Node $root): Navigator
    {
        $this->root = $root;
        return $this;
    }
    
    public function setPath(Path $path): Navigator
    {
        $this->path = $path;
        return $this;
    }
    
    /**
     * @return Node
     * @throws NodeNotFound
     */
     public function navigate():Node
     {
        return $this->traverse($this->root, $this->path);
     }

    /**
     * @param Node $component
     * @param Path|null $path
     * @return Node
     * @throws NodeNotFound
     */
     private function traverse(Node $component, ?Path $path):Node
     {
        if($path === null) return $component;
        $child = $component->getChild($path->getName());
        return $this->traverse($child, $path->getNext());
     }
}
```
####
* **Node-Based Routing:** Everything in a `netPhramework` application 
is a `Node` (component) in a tree (composite pattern). This includes static 
pages, data-driven views, and even actions like redirects. This provides a 
consistent and powerful way to structure an application's navigation and 
endpoints.
####
* **Decoupled URI Interpretation:** The `RequestInterpeter` uses the 
`UriAdapter` to transform a uri such as `/patients/8/appointments/17/edit` 
to a linked list `Path` that is used to navigate through a composite tree 
to the requested `Node`.
####
* **Data-Driven Assets:** The framework excels at generating application 
features directly from your database schema. The `AssetBuilder` allows you 
to declaratively build complete CRUD (Create, Read, Update, Delete) interfaces 
and presentation views for your database tables with a fluent and intuitive API.
####
* **Decoupled Architecture:** A key focus is the decoupling of the data layer 
from the presentation layer. For example, the `RecordSet` (which manages data 
fetching) is distinct from the `Asset` (which represents the feature in the 
application's node tree), allowing URL structures and application logic to be 
independent of database schema naming.
####
* **Strategy-Based Customization:** Instead of being forced into rigid 
structures, many components, especially for UI generation, can be configured 
with `Strategy` objects. This allows for powerful customization (e.g., 
defining custom table columns, adding aggregate data like totals) without 
having to modify the core framework components, following the Open/Closed 
Principle.

## Key Features

* **Hierarchical Node-Based Routing:** Any component that implements the `Node` 
interface can be part of the application structure.

* **Database Abstraction & Mapping:** A clean abstraction layer for database 
operations (`Select`, `Insert`, etc.) and a powerful data mapping layer 
(`Record`, `RecordSet`, `FieldSet`).

* **Sophisticated Record Table Generation:** A highly decoupled system for 
building data tables with advanced features:
    * Server-side filtering with multiple conditions (`AND`/`OR`).
    * Multi-column sorting.
    * Pagination.
    * Fully customizable column sets via `ColumnSetStrategy`.
    * Ability to add aggregate data (like page and grand totals) 
      via `ViewStrategy`.

* **Declarative Asset Building:** `PassiveAssetBuilder` and `ActiveAssetBuilder` 
allow for the rapid creation of nodes for Browse, adding, editing, and deleting 
records.

* **Role-Based Access Control:** A structural approach to security where 
different application configurations can be loaded based on `UserRole`, 
effectively defining which nodes are accessible to different types of users.

* **Component-Based Rendering:** A flexible `Viewable` system with a 
`ViewFactory` for assembling complex views from smaller, reusable parts.

## Example: Configuring a Browseable Asset

Building a complete, filterable, and paginated view of a database table can be 
declared very cleanly. The `PassiveAssetBuilder` is used to define the 
user-facing nodes for an asset.

```php
// In an application Configuration class...

// 1. Define custom strategies for columns and final view tweaks
$columnStrategy = new ColumnSetStrategy();
$viewStrategy   = new TableViewStrategy(); // Example for adding totals

// 2. Use the builder to configure the "patients" asset
new PassiveAssetBuilder($this->mapper, $root)
    // Add a "Browse" node (named '' for the asset's index)
    // and configure it with our custom strategies.
    ->includeBrowse($columnStrategy, $viewStrategy)

    // Add nodes for creating and editing records
    ->includeAdd(new ChildRecordFormStrategy($linkField))
    ->includeEdit(new ChildRecordFormStrategy($linkField))

    // Finalize and add the asset to the root directory
    ->commit('my-asset');
```

## The `recordTable` Architecture

The data table generation is a great example of the framework's design 
philosophy. The flow is orchestrated by the `ViewBuilder`:

1.  A `query\Query` object holds the user's request (filter, sort, page).
2.  The `ViewBuilder` instantiates a `collation\Collator`.
3.  The `Collator` takes the `Query` and uses the `rowSet\RowRegistry` 
(which holds the `RecordSet` and `ColumnSet`) to process the full set of 
record IDs through `select()`, `sort()`, and `paginate()` methods.
4.  It produces a `collation\CollationMap`, a DTO containing the different 
resulting sets of IDs (filtered, sorted, paginated).
5.  The `ViewBuilder` then uses a `ViewFactory` to assemble the final 
`RecordTable` view. The factory uses the `CollationMap` and a 
`rowSet\RowSetFactory` to create the final `RowSet` for display with the 
correct (paginated) IDs.

This pipeline ensures that each component has a single, well-defined 
responsibility, making the system powerful and maintainable.

-----
To get started, install netPhramework outside your web directory, 
then put two files in your web directory:
```apacheconf
# .htaccess
Options +FollowSymLinks
RewriteEngine On
RewriteCond %{REQUEST_URI} !\.
RewriteRule ^.+$ /index.php [L]

```
```php
// index.php
require_once "/path/to/netPhramework/boostrap/Loader.php";
use netPhramework\bootstrap\Controller;
use netPhramework\bootstrap\SiteContext;
new Controller()->run(new SiteContext());

```
This should get you the getting-started page with further instructions.