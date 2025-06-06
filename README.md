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
        throw new NodeNotFound("Not Found: $name");
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
 
    public function getPath():MutablePath
    {
        if(!preg_match('|^/([^?]*)|', $this->uri, $matches))
            throw new InvalidUri("Invalid Uri: $this->uri");
        $names = explode('/', $matches[1]);
        $path  = new MutablePath(array_shift($names));
        $this->traverseArray($path, $names);
        return $path;
    }

    private function traverseArray(MutablePath $path, array $names):void
    {
        if(count($names) === 0) return;
        $path->append(array_shift($names));
        $this->traverseArray($path->getNext(), $names);
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
     * @param Node $node
     * @param Path|null $path
     * @return Node
     * @throws NodeNotFound
     */
     private function traverse(Node $node, ?Path $path):Node
     {
        if($path === null) return $node;
        $child = $node->getChild($path->getName());
        return $this->traverse($child, $path->getNext());
     }
}
```
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
require_once "/path/to/netPhramework/bootstrap/Loader.php";
use netPhramework\bootstrap\Controller;
use netPhramework\bootstrap\SiteContext;
new Controller()->run(new SiteContext());

```
This should get you the getting-started page with further instructions.