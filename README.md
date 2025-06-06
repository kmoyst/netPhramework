# netPhramework

`netPhramework` is a modern, object-oriented PHP framework designed for building highly structured, data-driven web applications. It is built from the ground up with a strong emphasis on clear separation of concerns, flexibility, and a powerful, declarative approach to application configuration.

The core philosophy is centered around a hierarchical **Node-based routing system** and a robust **data mapping layer**, allowing developers to rapidly build complex database-backed features with clean, maintainable, and testable code.

## Core Concepts

* **Node-Based Routing:** Everything in a `netPhramework` application is a `Node` in a traversable tree. This includes static pages, data-driven views, and even actions like redirects. This provides a consistent and powerful way to structure an application's navigation and endpoints.
* **Data-Driven Assets:** The framework excels at generating application features directly from your database schema. The `AssetBuilder` allows you to declaratively build complete CRUD (Create, Read, Update, Delete) interfaces and presentation views for your database tables with a fluent and intuitive API.
* **Decoupled Architecture:** A key focus is the decoupling of the data layer from the presentation layer. For example, the `RecordSet` (which manages data fetching) is distinct from the `Asset` (which represents the feature in the application's node tree), allowing URL structures and application logic to be independent of database schema naming.
* **Strategy-Based Customization:** Instead of being forced into rigid structures, many components, especially for UI generation, can be configured with `Strategy` objects. This allows for powerful customization (e.g., defining custom table columns, adding aggregate data like totals) without having to modify the core framework components, following the Open/Closed Principle.

## Key Features

* **Hierarchical Node-Based Routing:** Any component that implements the `Node` interface can be part of the application structure.
* **Database Abstraction & Mapping:** A clean abstraction layer for database operations (`Select`, `Insert`, etc.) and a powerful data mapping layer (`Record`, `RecordSet`, `FieldSet`).
* **Sophisticated Record Table Generation:** A highly decoupled system for building data tables with advanced features:
    * Server-side filtering with multiple conditions (`AND`/`OR`).
    * Multi-column sorting.
    * Pagination.
    * Fully customizable column sets via `ColumnSetStrategy`.
    * Ability to add aggregate data (like page and grand totals) via `ViewStrategy`.
* **Declarative Asset Building:** `PassiveAssetBuilder` and `ActiveAssetBuilder` allow for the rapid creation of nodes for Browse, adding, editing, and deleting records.
* **Role-Based Access Control:** A structural approach to security where different application configurations can be loaded based on `UserRole`, effectively defining which nodes are accessible to different types of users.
* **Component-Based Rendering:** A flexible `Viewable` system with a `ViewFactory` for assembling complex views from smaller, reusable parts.

## Example: Configuring a Browseable Asset

Building a complete, filterable, and paginated view of a database table can be declared very cleanly. The `PassiveAssetBuilder` is used to define the user-facing nodes for an asset.

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

The data table generation is a great example of the framework's design philosophy. The flow is orchestrated by the `ViewBuilder`:

1.  A `query\Query` object holds the user's request (filter, sort, page).
2.  The `ViewBuilder` instantiates a `collation\Collator`.
3.  The `Collator` takes the `Query` and uses the `rowSet\RowRegistry` (which holds the `RecordSet` and `ColumnSet`) to process the full set of record IDs through `select()`, `sort()`, and `paginate()` methods.
4.  It produces a `collation\CollationMap`, a DTO containing the different resulting sets of IDs (filtered, sorted, paginated).
5.  The `ViewBuilder` then uses a `ViewFactory` to assemble the final `RecordTable` view. The factory uses the `CollationMap` and a `rowSet\RowSetFactory` to create the final `RowSet` for display with the correct (paginated) IDs.

This pipeline ensures that each component has a single, well-defined responsibility, making the system powerful and maintainable.

-----

