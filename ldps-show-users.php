<?php
/**
 * Plugin Name: Show Users
 * Plugin URI: https://github.com/jjjjcccjjf/ldps-show-users
 * Description: A wordpress plugin that utilises a static URL to display a virtual page of https://jsonplaceholder.typicode.com/users in table format
 * Version: 1.0
 * Author: Lorenzo Dante
 * Author URI: mailto:lorenzodante.dev@gmail.com
 */

declare(strict_types=1);

require 'vendor/autoload.php';
use jjjjcccjjf\ShowUsers;

// do not allow direct access
defined('ABSPATH') or die();

global $ldpsShowUsers;
$ldpsShowUsers = new \jjjjcccjjf\ShowUsers\LdpsShowUsers();