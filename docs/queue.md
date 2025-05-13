# Queue System for FFP Event Platform

The FFP Event Platform utilizes Laravel's queue system to handle time-consuming tasks in the background, ensuring that the application remains responsive to user requests. This is particularly important for operations such as sending emails and notifications.

## Management with PM2

The queue workers are managed using PM2, a process manager that helps ensure the workers are always running and can be easily monitored and restarted.

### Starting the Queue Worker

To start the queue worker, the following command is used:

```bash
php artisan queue:work --tries=3 --timeout=90 --name "ffp-events-queue"
```

**Command Breakdown:**

*   `php artisan queue:work`: This is the fundamental Laravel Artisan command to start a queue worker process. The worker will listen for jobs on the configured queue connections and process them as they arrive.
*   `--tries=3`: This option specifies that the worker should attempt to process a job a maximum of 3 times. If a job fails after these attempts, it will be marked as a failed job and typically moved to a `failed_jobs` table (if configured).
*   `--timeout=90`: This option sets a timeout of 90 seconds for each job. If a job runs longer than this duration, the worker will stop attempting to process it, and the job will be considered to have timed out. This prevents long-running jobs from blocking the worker indefinitely.
*   `--name "ffp-events-queue"`: When using PM2 to manage the worker, this option assigns a specific name ("ffp-events-queue") to the PM2 process. This makes it easier to identify, monitor, and manage this particular worker process using PM2 commands (e.g., `pm2 list`, `pm2 logs ffp-events-queue`).

For more details on managing the queue worker with PM2, such as ensuring it starts on boot and other PM2 commands, please refer to the `deployement` document.