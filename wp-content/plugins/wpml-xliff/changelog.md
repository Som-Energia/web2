**0.9.4**

* **Improvement**
	* Updated xliff generator to generate XLIFF file in multiple versions
* **Fix**
	* After plugin update it was not possible to import XLIFF file; now it's fixed
	* Handled case where ICL_PLUGIN_PATH constant is not defined (i.e. when plugin is activated before WPML core)
	* Fixed Korean locale in .mo file name

**0.9.2**

* **Improvement**
	* Updated xliff generator to use xmlns instead of DTD declaration
* **Fix**
	* Improved SSL support for CSS and JavaScript files
	* Updated translations
	* Updated links to wpml.org
	* Increased initialization priority, in order to load the plugin after Translation Management is loaded
