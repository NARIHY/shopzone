# Permission Seeder

## Overview

The `PermissionSeeder` is a Laravel database seeder designed to dynamically create permissions for each model in the application. This seeder generates permissions based on predefined actions and model names. It is especially useful in applications that follow role-based access control (RBAC), where permissions are needed for various actions on models.

## Requirements

* **Laravel**: This seeder is designed to work with Laravel 12.x and higher.
* **Permissions Configuration**: The seeder relies on permissions being configured in the `config/permissions.php` file.

## How It Works

1. **Actions**: The seeder retrieves predefined actions from the `config('permissions.actions')` configuration file. These actions are typically the verbs associated with models, such as "create", "update", "delete", "view", etc.

2. **Models**: The seeder scans the `app/Models` directory for all models (excluding `User` and `BaseModel` by default). It creates a permission for each combination of model and action.

3. **Permission Generation**: For every model and action pair, the seeder generates a permission with a name like `model_name.action_name` (e.g., `post.create`, `post.update`) and a description like "Ability to create on Post".

4. **First or Create**: If the permission already exists in the database, it will not be duplicated. The seeder ensures that the permission is created only if it doesn't already exist.

5. **Result**: After running the seeder, you will have a complete set of permissions for each model and action in your application.

## Usage

1. Add your actions in the `config/permissions.php` file. Example:

   ```php
   return [
       'actions' => ['create', 'view', 'update', 'delete'],
   ];
   ```

2. Run the seeder with the following Artisan command:

   ```bash
   php artisan db:seed --class=PermissionSeeder
   ```

3. This will automatically generate the necessary permissions in your database.

## Example Permission Generation

Assume you have a `Post` model and the following actions in your `config/permissions.php`:

```php
return [
    'actions' => ['create', 'view', 'update', 'delete'],
];
```

The seeder will create the following permissions:

* `post.create` – Ability to create on Post
* `post.view` – Ability to view on Post
* `post.update` – Ability to update on Post
* `post.delete` – Ability to delete on Post

## Customization

* **Model Exclusion**: By default, the `User` and `BaseModel` models are excluded from permission generation. If you need to include them or exclude additional models, you can modify the filtering logic in the seeder.
* **Permission Actions**: The actions array in `config/permissions.php` can be modified to fit your application's requirements (e.g., add custom actions like `publish`, `archive`, etc.).

## Notes

* Make sure to run this seeder only once or when you add new models or actions, as it will create new permissions in the database.
* This is particularly useful in applications that use policies or gates for authorization, allowing you to dynamically manage permissions for various models.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
