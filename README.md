# Grav Plugin: Markdown Yet Another Linker

The **Markdown Yet Another Linker** Plugin is an extension for [Grav CMS](http://github.com/getgrav/grav). Adds the ability to create links with less typing in Markdown

## Installation

Installing the Markdown Yet Another Linker plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](http://learn.getgrav.org/advanced/grav-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install markdown-yalinker

This will install the Markdown Yet Another Linker plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/markdown-yalinker`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `markdown-yalinker`. You can find these files on [GitHub](https://github.com/HuidaeCho/grav-plugin-markdown-yalinker) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/markdown-yalinker

> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com/HuidaeCho/grav-plugin-markdown-yalinker/blob/master/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/markdown-yalinker/markdown-yalinker.yaml` to `user/config/plugins/markdown-yalinker.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
```

Note that if you use the Admin Plugin, a file with your configuration named markdown-yalinker.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

## Usage

| YALink | Markdown |
| ------ | -------- |
| `[[Page Title]]` | `[Page Title](page-title)` |
| `[[Page Title\|Link Text]]` | `[Link Text](page-title)` |
| `[[Slashes//in Page Title]]` | `[Slashes/in Page Title](slashes-in-page-title)` |
| `[[../Some Path/Page Title]]` | `[Page Title](../some-path/page-title)` |
| `[[../Some Path/Page Title\|]]` | `[../Some Path/Page Title](../some-path/page-title)` |
| `[[/Root Path/Page Title]]` | `[Page Title](/root-path/page-title)` |
| `[[https://example.com]]` | `<https://example.com>` |
| `[[mailto:me@example.com]]` | `<mailto:me@example.com>` |
| `[[mail:me@example.com]]` | `[me@example.com](mailto:me@example.com)` |

## Demo

https://demo.isnew.info/grav/markdown-yalinker

## Credits

Author: Huidae Cho

Adopted the `slug` function from the Admin plugin (`admin/classes/utils.php`).
