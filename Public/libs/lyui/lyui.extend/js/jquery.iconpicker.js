(function($) {
    $.fn.iconpicker = function(options) {

        var self = this;
        this.buttonSize = 'sm';
        this.columns = 10;
        this.height = 400;
        this.extend(options);
        this.open = false;
        this.allowedSizes = ["sm", "lg", "2x", "3x", "4x", "5x"];

        //Templates
        this.templates = {
            filter: "<div class='row'><div class='col-xs-12 form-group'><div class='icon-filter-box input-group'><span class='input-group-addon'>Filter: </span><input type='text' class='icon-filter form-control'></div></div></div>",
            sizes: "<div class='row'><div class='col-xs-12 btn-group'><a class='btn btn-default' href='#' data-size='sm'>SM</a><a class='btn btn-default' href='#' data-size='lg'>LG</a><a class='btn btn-default' href='#' data-size='2x'>2X</a><a class='btn btn-default' href='#' data-size='3x'>3X</a><a class='btn btn-default' href='#' data-size='4x'>4X</a><a class='btn btn-default' href='#' data-size='5x'>5X</a></div></div>",
        };

        //Icon List
        this.icons = options.icons;

        //Bind open menu function to element click
        $(this).on("click", function(e) {
            self.$el = $(e.currentTarget);
            if ($(e.currentTarget).find(".icon-menu").length) {
                self.closeMenu();
            } else {
                self.openMenu();
            }
        });

        //Bind event to icon click
        $(this).on("click", ".icon", function(e) {
            e.stopPropagation();
            var icon = $(e.currentTarget).data("icon");
            if (self.clickCallback !== undefined) {
                self.clickCallback(icon);
            } else {
                self.$el.find("> .form-control").val(icon).focus().select();
            }
            self.closeMenu();
        });

        //Stop menu from closing if click in menu
        $(this).on("click", ".icon-menu", function(e) {
            e.stopPropagation();
        });

        //Bind event to filter input
        $(this).on("keyup", ".icon-filter", function(e) {
            var str = $(e.target).val();
            self.doFilter(str);
        });

        //Bind event to size menu
        $(this).on("click", ".btn-group .btn", function(e) {
            var size = ($(e.currentTarget).data('size') == 'sm') ? '' : 'fa-' + $(e.currentTarget).data('size') ;
            self.$sizeSelector.find('.btn').removeClass("active");
            $(e.currentTarget).addClass("active");
            self.$container.find('i').removeClass('fa-lg fa-2x fa-3x fa-4x fa-5x').addClass(size);
            self.resize();
        });

        //Create html element
        this.createMenu = function() {
            this.icons = eval(this.icons);
            this.$menu = $("<div>", {class: "icon-menu", style: "height:" + this.height + "px"});
            if (this.filter !== false) {
                this.$filter = $(this.templates.filter);
                this.$menu.append(this.$filter);
            }
            if (this.sizeSelector !== false) {
                this.$sizeSelector = $(this.templates.sizes);
                this.$sizeSelector.find('a[data-size=' + this.buttonSize + ']').addClass('active');
                this.$menu.append(this.$sizeSelector);
            }
            this.$container = $("<div>", {class: "icon-container", style: "height:" + this.innerHeight + "px"});
            for (var i in this.icons) {
                var size = ($.inArray(this.buttonSize, this.allowedSizes) > -1) ? " fa-" + this.buttonSize : "" ;
                var button = $("<a>", {class: "icon", title: this.icons[i].name, "data-icon": this.icons[i].selector, "data-filter": this.icons[i].filter});
                button.html("<i class='fa fa-fw " + this.icons[i].selector + size + "'></i>");
                this.$container.append(button);
            }
            this.$menu.append(this.$container);
        }

        //Open Menu
        this.openMenu = function(e) {
            this.open = true;
            $(this.$el).append(this.$menu);
            this.resize();
            this.$menu.find('.icon-filter').focus();
        }

        //Close Menu
        this.closeMenu = function() {
            this.open = false;
            this.$menu.detach();
        }

        //Resize menu
        this.resize = function(columns) {
            this.scrollbarWidth = this.scrollbarWidth || this.getScrollbarWidth();
            var width = Math.ceil($('.icon').outerWidth(true) * this.columns + this.scrollbarWidth) + "px";
            var height = this.height - (parseInt(this.$menu.css('padding-top')) * 2);
                height -= (this.filter !== false) ? this.$filter.outerHeight(true) : 0 ;
                height -= (this.sizeSelector !== false) ? this.$sizeSelector.outerHeight(true) : 0 ;
            this.$container.css({width: width, height: height});

        }

        //Filter function
        this.doFilter = function(str) {
            if (str !== "") {
                $(this).find("a.icon[data-filter*='" + str + "']").show();
                $(this).find("a.icon:not([data-filter*='" + str + "'])").hide();
            } else {
                $(this).find("a.icon").show();
            }
        }

        //Uppercase words function
        this.ucwords = function(str) {
            return (str.replace(/-/g, ' ') + '')
                .replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function($1) {
                    return $1.toUpperCase();
            });
        }

        //Object sorting function
        this.sortObj = function(obj) {
            var key,
                tempArry = [],
                i,
                tempObj = {};
            for ( key in obj ) {
                tempArry.push(key);
            }
            tempArry.sort(
                function(a, b) {
                    return a.toLowerCase().localeCompare( b.toLowerCase() );
                }
            );
            for ( i = 0; i < tempArry.length; i++ ) {
                tempObj[ tempArry[i] ] = obj[ tempArry[i] ];
            }
            return tempObj;
        }

        //Scrollbar width
        this.getScrollbarWidth = function() {
            var $inner = $('<div style="width: 100%; height:200px;">test</div>'),
                $outer = $('<div style="width:200px;height:150px; position: absolute; top: 0; left: 0; visibility: hidden; overflow:hidden;"></div>').append($inner),
                inner = $inner[0],
                outer = $outer[0];
            $('body').append(outer);
            var width1 = inner.offsetWidth;
            $outer.css('overflow', 'scroll');
            var width2 = outer.clientWidth;
            $outer.remove();
            return (width1 - width2);
        }

        self.createMenu();

        return this;
    };
}( jQuery ));