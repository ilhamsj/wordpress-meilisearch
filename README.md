# WordPress Custom Sync

A lightweight WordPress plugin that triggers external webhooks when posts or taxonomies are created, updated, deleted, or when post statuses change.

## Features

- Hooks into WordPress actions for post and taxonomy events
- Sends JSON POST requests with relevant data to a configurable webhook URL
- Lightweight, secure, and easy to customize
- Includes admin settings page for webhook configuration

## Events Tracked

The plugin tracks the following WordPress events:

### Post Events

- `save_post` - Post creation and updates
- `delete_post` - Post deletion
- `transition_post_status` - Post status changes
- `wp_trash_post` - Post moved to trash
- `untrash_post` - Post restored from trash

### Taxonomy Events

- `created_term` - Term creation
- `edited_term` - Term updates
- `delete_term` - Term deletion

## Installation

1. Upload the entire `wordpress-custom-sync` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > WP Custom Sync to configure your webhook URL

## Configuration

The plugin adds a settings page under Settings > WP Custom Sync where you can configure the webhook URL. This is the endpoint where all webhook notifications will be sent.

## Webhook Data Format

The webhook sends a JSON payload with the following structure:

### Post Events

```json
{
  "post_id": 123,
  "title": "Example Post Title",
  "status": "publish",
  "post_type": "post",
  "event_type": "create|update|delete|status_change|trash|untrash",
  "old_status": "draft", // Only for status_change events
  "new_status": "publish", // Only for status_change events
  "object_type": "post",
  "timestamp": "2023-05-25 12:34:56"
}
```

### Taxonomy Events

```json
{
  "term_id": 45,
  "taxonomy": "category",
  "name": "Example Category",
  "slug": "example-category",
  "event_type": "create|update|delete",
  "object_type": "term",
  "timestamp": "2023-05-25 12:34:56"
}
```

## Webhook Receiver Example

A sample webhook receiver script is included in the repository (`webhook-receiver-example.php`). This script demonstrates how to receive and process the webhook data sent by the plugin.

## Customization

The plugin is designed to be easily customizable. Here are some examples of modifications you might want to make:

### Adding Custom Fields to the Webhook Payload

You can modify the handler functions to include additional data in the webhook payload:

```php
public function handle_save_post($post_id, $post, $update) {
    // ... existing code ...

    // Add custom fields to the post data
    $post_data['custom_field'] = get_post_meta($post_id, 'custom_field', true);
    $post_data['categories'] = wp_get_post_categories($post_id, array('fields' => 'names'));

    $this->send_webhook('post', $post_data);
}
```

### Filtering Posts by Type

If you only want to trigger webhooks for specific post types:

```php
public function handle_save_post($post_id, $post, $update) {
    // ... existing code ...

    // Only trigger for specific post types
    $allowed_post_types = array('post', 'product');
    if (!in_array($post->post_type, $allowed_post_types)) {
        return;
    }

    // ... rest of the function ...
}
```

## Security Considerations

- The plugin includes a custom header (`X-WordPress-Webhook: wordpress-custom-sync`) in the webhook request to help identify the source.
- Consider implementing additional authentication methods (such as JWT tokens or signature verification) in your webhook receiver.
- The webhook URL is sanitized using `esc_url_raw()` to prevent malicious inputs.

## License

This plugin is licensed under the GPL v2 or later.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---
