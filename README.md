<p align="center"><img src="https://raw.githubusercontent.com/achappard/bugtrackly/refs/heads/master/public/images/bugtrackly-logo.png" width="400" alt="BugTrackly Logo"></p>
<p align="center">
<a href="https://github.com/achappard/bugtrackly/releases/latest"><img src="https://img.shields.io/github/v/release/achappard/bugtrackly" alt="Release"></a>
<a href="https://opensource.org/license/gpl-3-0" target="_blank"><img src="https://img.shields.io/github/license/achappard/bugtrackly" alt="License"></a>
<img src="https://img.shields.io/github/contributors/achappard/bugtrackly" alt="Contributors">
</p>

## About BugTrackly

BugTrackly is an innovative bug tracking and management solution designed specifically for freelancers in the technology sector. Developed with [Laravel](https://laravel.com), one of the best modern PHP platforms, BugTrackly helps you to effectively manage the problems encountered in your projects while offering a simple, intuitive interface tailored to your needs. Here are the application's key features:


- **Track bugs by project**: classify bugs by severity, priority or status, and access the action history for each problem.
- **Customised notifications**: Stay informed in real time of important updates via e-mail or system notifications.
- **Intuitive user interface**: ergonomics designed to minimise learning time and maximise productivity.

BugTrackly allows you to centralise the management of bugs in your projects, collaborate easily and gain in efficiency.


## Security Vulnerabilities

If you discover a security vulnerability within BugTrackly, please send an e-mail to Aurélien Chappard via [aurelien.chappard@deefuse.fr](mailto:aurelien.chappard@deefuse.fr). All security vulnerabilities will be promptly addressed.

## License

The BugTrackly application is open-sourced software licensed under the [GPL-3.0 license](https://opensource.org/license/gpl-3-0).


---

## Documentation

- Download or clone the repository : `git clone https://github.com/achappard/bugtrackly.git mbugtrackly-instance`
- Install php dependencies by running `composer install`
- Copy the `.env.example` file in a new `.env` file
- Configure application by editing the `.env` file; in particular, access to the database. All the available configurations are documented in the file `config/bugtrackly.php`
- Migrate the database with `php artisan migrate` command.
- Create **the first admin user** by running the interactive command `php artisan bugtrackly:default_user`.
- Run `npm install`
- Run `npm run build`
