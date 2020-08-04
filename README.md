# Markdown Yet Another Linker

**This README.md file should be modified to describe the features, installation, configuration, and general usage of the plugin.**

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

* `[[Hello World!]]`: `<a href="hello-world">Hello World!</a>`
* `[[Hello World!|]]`: `<a href="hello-world">Hello World!</a>`
* `[[Hello World!|Link Text]]`: `<a href="hello-world">Link Text</a>`
* `[[/Hello World!]]`: `<a href="/hello-world">Hello World!</a>`
* `[[/Hello World!|]]`: `<a href="/hello-world">/Hello World!</a>`
* `[[/Hello World!|Link Text]]`: `<a href="/hello-world">Link Text</a>`
* `[[./Hello World!]]`: `<a href="./hello-world">Hello World!</a>`
* `[[./Hello World!|]]`: `<a href="./hello-world">./Hello World!</a>`
* `[[./Hello World!|Link Text]]`: `<a href="./hello-world">Link Text</a>`
* `[[../Hello World!]]`: `<a href="../hello-world">Hello World!</a>`
* `[[../Hello World!|]]`: `<a href="../hello-world">../Hello World!</a>`
* `[[../Hello World!|Link Text]]`: `<a href="../hello-world">Link Text</a>`
* `[[../Sibling World!/Slashes//in Page Title]]`: `<a href="../sibling-world/shasles-in-page-title">Slashes/in Page Title</a>`
* `[[../Sibling World!/Slashes//in Page Title|]]`: `<a href="../sibling-world/shasles-in-page-title">../Sibling World!/Slashes/in Page Title</a>`
* `[[../Sibling World!/Slashes//in Page Title|Link Text]]`: `<a href="../sibling-world/shasles-in-page-title">Link Text</a>`
* `[[https://example.com]]`: `<a href="https://example.com">https://example.com</a>`
* `[[https://example.com|]]`: `<a href="https://example.com">https://example.com</a>`
* `[[https://example.com|Example.com]]`: `<a href="https://example.com">Example.com</a>`
* `[[mailto:me@example.com]]`: `<a href="mailto:me@example.com">mailto:me@example.com</a>`
* `[[mailto:me@example.com|]]`: `<a href="mailto:me@example.com">mailto:me@example.com</a>`
* `[[mailto:me@example.com|me@example.com]]`: `<a href="mailto:me@example.com">me@example.com</a>`
* `[[mail:me@example.com]]`: `<a href="mailto:me@example.com">me@example.com</a>`
* `[[mail:me@example.com|]]`: `<a href="mailto:me@example.com">me@example.com</a>`
* `[[mail:me@example.com|me@example.com]]`: `<a href="mailto:me@example.com">me@example.com</a>`

## Credits

Adopted the `slug`' function from the Admin plugin (admin/classes/utils.php).

## To Do

- [ ] Future plans, if any
