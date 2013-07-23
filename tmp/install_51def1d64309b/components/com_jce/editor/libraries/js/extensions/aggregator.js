/* JCE Editor - 2.3.3 | 11 July 2013 | http://www.joomlacontenteditor.net | Copyright (C) 2006 - 2013 Ryan Demmer. All rights reserved | GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html */
var WFAggregator=WFExtensions.add('Aggregator',{aggregators:{},add:function(name,o){this.aggregators[name]=o||{};},get:function(name){return this.aggregators[name]||null;},setup:function(options){var self=this;options=options||{};tinymce.each(this.aggregators,function(o,k){self.setParams(o,options);return self._call(o,'setup');});},getTitle:function(name){var f=this.get(name);if(f){return f.title;}
return name;},getType:function(name){var f=this.get(name);if(f){return f.getType();}
return'';},getValues:function(name,src){var f=this.get(name);if(f){return this._call(f,'getValues',src);}},setValues:function(name,data){var f=this.get(name);if(f){return this._call(f,'setValues',data);}},getAttributes:function(name,args){var f=this.get(name);if(f){return this._call(f,'getAttributes',args);}},setAttributes:function(name,args){var f=this.get(name);if(f){return this._call(f,'setAttributes',args);}},isSupported:function(args){var self=this,r,v;tinymce.each(this.aggregators,function(o){if(v=self._call(o,'isSupported',args)){r=v;}});return r;},getParam:function(name,param){var f=this.get(name);if(f){return f.params[param]||'';}
return'';},setParams:function(name,o){var f=this.get(name);if(f){tinymce.extend(f.params,o);}},onSelectFile:function(name){var f=this.get(name);if(f){return this._call(f,'onSelectFile');}},onInsert:function(name){var self=this,f=this.get(name);if(f){return self._call(f,'onInsert');}},_call:function(o,fn,vars){var f=o[fn]||function(){};return f.call(o,vars);}});