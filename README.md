# Show Users

A wordpress plugin that utilises a static URL to display a virtual page of [https://jsonplaceholder.typicode.com/users](https://jsonplaceholder.typicode.com/users) in table format

## Contents
* [System Requirements](#system-requirements)
* [Dependencies](#dependencies)
* [Installation](#installation)
    * [Composer installation](#composer-installation)
* [Usage](#usage)
    * [Dashboard link](#dashboard-link)
    * [Settings](#settings)
        * [Settings link](#settings-link)
        * [Settings page](#settings-link)
    * [Plugin virtual page](#plugin-virtual-page)
* [Notes](#notes)
    * [Composer support](#composer-support)
    * [Caching explanation](#caching-explanation)

## System Requirements
* PHP >=5.6
* Wordpress >= 5.4

## Dependencies 
* [Composer installers](https://github.com/composer/installers) - Used to customize the plugin installation path to *wp-content/plugins*

## Installation
### Composer installation
To install via composer, simply run the following command:  

`$ composer require jjjjcccjjf/ldps-show-users`  

and activate the plugin.

## Usage
The plugin, once activated, creates a virtual page on your wordpress site to access the default *show-users* url. I call it a *virtual page* because the plugin does not actually create an actual Page in the wordpress database. 

#### Dashboard link
For convenience, a link is included in the WP-admin dashboard to access the virtual page. *(Screenshots of the actual page can be found after the Settings section)*

![Dashboard link](https://i.imgur.com/tGZzDKK.png)

#### Settings
##### Settings link
You can access the settings page of the plugin either by the WP-admin sidebar

![Settings link](https://i.imgur.com/Iwv3bwV.png)

or at the Plugins page in WP-admin itself

![Plugin link](https://i.imgur.com/2BB2bHc.png)

##### Settings page
There are two options that could be configured in this plugin. They are listed below.

* **Use default style** - If checked, this option allows the plugin to use its own default stylesheet. Otherwise the plugin will just inherit the styles of the active theme. 
    * *(Default value: checked)*
* **Custom Page Slug** - This option allows the user to set their own url of choosing for the plugin's virtual page. 
    * *(Default value: 'show-users')*
    * *(Note: If you create a Page with the same slug, it will be overriden and the custom page will take precedence)*

![Settings page](https://i.imgur.com/8QVvOXd.png)

#### Plugin virtual page
Once the link is visited, the page will then display the users table fetched asynchronously from the API.

![Plugin virtual page](https://i.imgur.com/plsj7FD.png)

Each user's details can be then found by clicking on any information on the row, making another asynchronous call to the API.

![Details view](https://i.imgur.com/Gt8ESqv.png)

## Notes
#### Composer support
A full composer support has been added to attend to modern wordpress development standard of using wordpress alongside composer such as installing the wordpress core itself, configuring plugins, themes, etc. But this is not a requirement and the plugin could also be installed using the traditional means by copy-and-pasting it to the wordpress plugin directory.

#### Caching explanation
I opted not to cache the JSON data fetched from the API as I found out that the browsers I've tested with already caches the response themselves (tested on Opera, Firefox, and Google Chrome). Since the plugin requirements didn't specify database transactions even at minimum, I figured using server-side caching is a bit overkill so I ultimately had to rely on the browser's native caching capabilities.