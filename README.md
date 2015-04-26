# Notifygram
Notifygram is a free service for sending notifications directly to Telegram.

###Connect Notifygram
1. Connect Notifygram at https://notifygram.org
2. Create a project
3. Add telegram users you want to receive notifications

###Embed Notifygram.class.php
```
<?php
require_once("Notifygram.class.php");
$notifygram = new Notifygram($project_api_key, $project_token);
$notifygram->notify($message);
?>
```

####Usage of param 'show_project_name'
By default the #project_name is appended at the beginning of each message.
With param 'show_project_name'=0 you can disable this feature.
```
<?php
$notifygram = new Notifygram($project_api_key, $project_token, 0);
?>
```

### Notifygram API
API is available here: https://notifygram.org/api

###Feedback
If you have any ideas, feel free to write me directly in Telegram: @ilzheev
