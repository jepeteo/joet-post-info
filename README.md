# joet-post-info

A wordpress plugin to display in the beginning or in the end of the post/page the following info:

- word count
- charactes count
- reading time in minutes (180 words per minute)

You can enable / disable all the above information from the plugin's settings page.


## Classes
JoetPluginPostStatistics - Main class

## Functions

**ifWrap()** - Checks if the current query is the main query, AND check if the current page is a single post or page AND checks if any of the options (joet_wordcount, joet_charcount, joet_readtime) is enabled then calls the method createHTML. If the condition fails, it simply returns the $content.

**createHTML($content)** - Display the post's / page's content along with the info of the plugin.

**settings**() - displays the plugin's settings.

**sanitizeLocation($input)** - checks if input is 0 or 1 and returns the input. If the condition fails, displays an error.

**locationHTML()** - Display Location option in settings page

**headlineHTML()** - Display Headline option in settings page

**checkboxHTML($args)** - Display checkboxes option in settings page

**adminPage()** - creates the settings page

**joetHTML()** - html for the settings page

## Methods

createHTML - Displays the post information based on settings
