var mootabs = new Class({
                Implements: [Options, Events],
                options: {
                        width:                          '100%',
                        height:                         '650px',
                        changeTransition:       Fx.Transitions.Bounce.easeOut,
                        duration:                       1000,
                        mouseOverClass:         'active',
                        activateOnLoad:         'first',
                        useAjax:                        true,
                        ajaxUrl:                        '',
                        ajaxOptions:            { method:'get' },
                        ajaxLoadingText:        'Loading...'
                },
               
                initialize: function(element, options) {
                        this.setOptions(options);
                        this.el = $(element);
                        this.elid = element;
                        this.el.setStyles({
                                height: this.options.height,
                                width: this.options.width
                        });
               
                        this.titles = $$('#' + this.elid + ' ul.mootabs_title li');
                        this.panelHeight = this.el.getSize().y - (this.titles[0].getSize().y + 4);
                        this.panels = $$('#' + this.elid + ' .mootabs_panel');
               
                        this.panels.setStyle('height', this.panelHeight);
               
                        this.titles.each(function(item) {
                                item.addEvent('click', function(){
                                        item.removeClass(this.options.mouseOverClass);
                                        this.activate(item);
                                }.bind(this)
                        );
                       
                        item.addEvent('mouseover', function() {
                                if(item != this.activeTitle){
                                        item.addClass(this.options.mouseOverClass);
                                }
                        }.bind(this));
                       
                        item.addEvent('mouseout', function() {
                                if(item != this.activeTitle){
                                        item.removeClass(this.options.mouseOverClass);
                                }
                        }.bind(this));
                }.bind(this));
                if(this.options.activateOnLoad != 'none'){
                        if(this.options.activateOnLoad == 'first'){
                                this.activate(this.titles[0], true);
                        }
                        else{
                                this.activate(this.options.activateOnLoad, true);       
                        }
                }
        },
       
        activate: function(tab, skipAnim){
                if(! $defined(skipAnim)){
                        skipAnim = false;
                }
                if($type(tab) == 'string'){
                        myTab = $$('#' + this.elid + ' ul li').filter('[title='+tab+']')[0];
                        tab = myTab;
                }
                if($type(tab) == 'element'){
                        var newTab = tab.getProperty('title');
                        this.panels.removeClass('active');
                        this.activePanel = $(newTab);
                       
                        this.activePanel.addClass('active');
                        if(this.options.changeTransition != 'none' && skipAnim==false){
                                this.activePanel.setStyle('height', 0);
                                var changeEffect = new Fx.Elements(this.activePanel, {duration: this.options.duration, transition: this.options.changeTransition});
                                changeEffect.start({
                                        '0': {
                                                'height': [0, this.panelHeight]
                                        }
                                });
                        }
                        this.titles.removeClass('active');
                        tab.addClass('active');
                        this.activeTitle = tab;
                        if(this.options.useAjax){
                                this.getContent();
                        }
                }
        },
       
        getContent: function(){
                this.activePanel.set('text', this.options.ajaxLoadingText);
                var newOptions = { url: this.options.ajaxUrl + '?tab=' + this.activeTitle.get('title'), update: this.activePanel.get('id') } ;
                this.options.ajaxOptions = $extend(this.options.ajaxOptions, newOptions);
                var req = new Request.HTML(
                        this.options.ajaxOptions
                ).send();
        },
       
        addTab: function(title, label, content){
                var newTitle = new Element('li', {
                        'title': title
                });
                newTitle.appendText(label);
                this.titles.include(newTitle);
                $$('#' + this.elid + ' ul').adopt(newTitle);
                newTitle.addEvent('click', function() {
                        this.activate(newTitle);
                }.bind(this));
               
                newTitle.addEvent('mouseover', function() {
                        if(newTitle != this.activeTitle){
                                newTitle.addClass(this.options.mouseOverClass);
                        }
                }.bind(this));
                newTitle.addEvent('mouseout', function() {
                        if(newTitle != this.activeTitle){
                                newTitle.removeClass(this.options.mouseOverClass);
                        }
                }.bind(this));

                var newPanel = new Element('div', {
                        'style': {'height': this.options.panelHeight},
                        'id': title,
                        'class': 'mootabs_panel'
                });
                if(!this.options.useAjax){
                        newPanel.setHTML(content);
                }
                this.panels.include(newPanel);
                this.el.adopt(newPanel);
        },
       
        removeTab: function(title){
                if(this.activeTitle.title == title){
                        this.activate(this.titles[0]);
                }

                $$('#' + this.elid + ' ul li').filter('[title='+title+']')[0].dispose();
                $$('#' + this.elid + ' .mootabs_panel').filter('[id='+title+']')[0].dispose();
        },
       
        next: function(){
                var nextTab = this.activeTitle.getNext();
                if(!nextTab) {
                        nextTab = this.titles[0];
                }
                this.activate(nextTab);
        },
       
        previous: function(){
                var previousTab = this.activeTitle.getPrevious();
                if(!previousTab) {
                        previousTab = this.titles[this.titles.length - 1];
                }
                this.activate(previousTab);
        }
});