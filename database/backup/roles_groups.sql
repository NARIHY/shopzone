INSERT INTO roles (roleName, description, is_active, created_at, updated_at) VALUES
('visitor', 'User with minimal access — can only browse public content.', true, NOW(), NOW()),
('customer', 'Registered user who can purchase or interact with products and services.', true, NOW(), NOW()),
('seller', 'User allowed to list and manage their own products or services.', true, NOW(), NOW()),
('public', 'Generic role for unauthenticated or public users.', true, NOW(), NOW()),
('advertiser', 'User or company responsible for managing advertisements or campaigns.', true, NOW(), NOW()),
('moderator', 'User responsible for overseeing content and managing community interactions.', true, NOW(), NOW()),
('administrator', 'User with management permissions over most system functions.', true, NOW(), NOW()),
('owner', 'Business or property owner with specific management rights.', true, NOW(), NOW()),
('super-admin', 'Highest-level role with full access to all system modules and configurations.', true, NOW(), NOW());

INSERT INTO groups (name, description, role_id, is_active, created_at, updated_at) VALUES
('General Users', 'Group for casual visitors and new users of the platform.', 1, true, NOW(), NOW()),
('Customers', 'Group of registered users who purchase or subscribe to products.', 2, true, NOW(), NOW()),
('Sellers', 'Group of merchants or service providers who offer listings or products.', 3, true, NOW(), NOW()),
('Public Access', 'Default group for non-authenticated site visitors.', 4, true, NOW(), NOW()),
('Advertising Team', 'Group managing promotional campaigns, ads, and marketing content.', 5, true, NOW(), NOW()),
('Moderation Team', 'Group that monitors user-generated content and handles reports.', 6, true, NOW(), NOW()),
('Administrators', 'Group with access to system configuration, user management, and permissions.', 7, true, NOW(), NOW()),
('Property Owners', 'Group of users who manage their own properties or listings on the platform.', 8, true, NOW(), NOW()),
('Super Admins', 'System-wide administrators with unrestricted permissions.', 9, true, NOW(), NOW());
