/**
 * 下拉树形选择(可多选)
 * 基于Bootstrap，且配合Light Year Admin使用
 * http://www.itshubao.com
 * yinqi<3331653644@qq.com>
 */
;(function($, window, document, undefined) {
    
    var lyearDropdownTree = function(ele, opt) {
        this.$element = ele,
        this.defaults = {
            multiSelect : true,      // 是否多选
            data : [],               // 数据
            jsonStr : ',',           // 数据分隔符
            selectedData : [],       // 初始化已经选中的ID
            relationParent : true,   // 是否关联父类(主要用于多选)
            relationChildren : true, // 是否关联子类(主要用于多选)
            checkHandler : null,     // 回调
        };
        this.configs = $.extend({}, this.defaults, opt);
        
        if ("undefined" == typeof (bootstrap.Tooltip.VERSION)) {
            this.bsVersion = "3";
        } else {
            if (/4\.[0-9]\.[0-9]/.test(bootstrap.Tooltip.VERSION)) {
                this.bsVersion = "4";
            } else if (/5\.[0-9]\.[0-9]/.test(bootstrap.Tooltip.VERSION)) {
                this.bsVersion = "5";
            } else {
                this.bsVersion = "3";
            }
        }
        
        this.renderData = function(data, element) {
            for (var i = 0; i < data.length; i++) {
                if (!element.is('li')) {
                    element.append('<li id="lyearDropdownTree'+data[i].id+'">'
                    +(this.configs.multiSelect?'<i class="mdi checkbox-box" aria-hidden="true"></i>':'<i class="mdi radio-box" aria-hidden="true"></i>')
                    +(this.bsVersion == 3 ? '<a href="#!" data-id="'+data[i].id+'">' : '<a href="#!" class="dropdown-item" data-id="'+data[i].id+'">')
                    +data[i].title+'</a></li>');
                } else {
                    element.find('ul').append('<li id="lyearDropdownTree'+data[i].id+'"><a href="#!" data-id="'+data[i].id+'">'+data[i].title+'</a>');
                }
                
                if (data[i].children != null && typeof data[i].children != 'undefined') {
                    $('#lyearDropdownTree'+data[i].id).append('<ul style="display:none"></ul>');
                    $('#lyearDropdownTree'+data[i].id).find('a').first().prepend('<span class="arrow"><i class="mdi mdi-menu-right" aria-hidden="true"></i></span>');
                    this.renderData(data[i].children, $('#lyearDropdownTree'+data[i].id).find('ul').first());
                }
            }
        };
    };
    
    lyearDropdownTree.prototype = {
        init : function() {
            var $thisLyearDropdownTree = this;
            this.$element.each(function() {
                $thisLyearDropdownTree.createDropdownTree($(this));
                $thisLyearDropdownTree.setSelectedItem($thisLyearDropdownTree.configs.selectedData);
            });
            
            return $thisLyearDropdownTree;
        },
        
        createDropdownTree : function($dropdownTree) {
            var $thisLyearDropdownTree = this,
                $currenTree = this.$element,
                $configs               = $thisLyearDropdownTree.configs;
            
            $dropdownTree.append('<ul class="dropdown-menu"></ul><i class="mdi mdi-menu-down lyear-cert"></i>');
            this.renderData($configs.data, $dropdownTree.find("ul").first());
            
            $(document).on("click", ".lyear-dropdown-tree .dropdown-menu li", function(e) {
                e.stopPropagation();
            });
            $currenTree.on("click", ".arrow", function(e){
                e.stopPropagation();
                $(this).empty();
                if ($(this).parents("li").first().find("ul").first().is(":visible")) {
                    $(this).prepend('<i class="mdi mdi-menu-right" aria-hidden="true"></i>');
                    $(this).parents("li").first().find("ul").first().hide();
                } else {
                    $(this).prepend('<i class="mdi mdi-menu-down" aria-hidden="true"></i>');
                    $(this).parents("li").first().find("ul").first().show();
                }
            });
           $currenTree.on('click', '.checkbox-box', function(e) {
                
                if ($configs.relationParent == true) {
                    $(this).removeClass('lyear-dropdown-tree-half-checked').toggleClass("lyear-dropdown-tree-checked");
                    
                    // 父类关联子类
                    if ($(this).hasClass('lyear-dropdown-tree-checked')) {
                        $(this).siblings('ul').find('.checkbox-box')
                            .not('.lyear-dropdown-tree-checked')
                            .removeClass('lyear-dropdown-tree-half-checked')
                            .addClass('lyear-dropdown-tree-checked');
                    } else {
                        $(this).siblings('ul').find('.checkbox-box')
                            .filter('.lyear-dropdown-tree-checked')
                            .removeClass('lyear-dropdown-tree-half-checked')
                            .removeClass('lyear-dropdown-tree-checked');
                    }
                } else {
                    // 如果不关联子类，点击时判断是否有lyear-dropdown-tree-half-checked，取消时新增回去
                    if ($(this).hasClass('lyear-dropdown-tree-half-checked')) {
                        $(this).removeClass('lyear-dropdown-tree-half-checked').addClass("lyear-dropdown-tree-checked");
                    } else {
                        $hasChecked = $(this).siblings('ul').find('.lyear-dropdown-tree-checked');
                        if ($hasChecked.length > 0) {
                            $(this).removeClass('lyear-dropdown-tree-checked').addClass("lyear-dropdown-tree-half-checked");
                        } else {
                            $(this).toggleClass("lyear-dropdown-tree-checked");
                        }
                    }
                }
                
                // 子类关联父类
                $(this).parent().parent().parents('li').each(function() {
                    var $all     = $(this).find('ul:first').find('.checkbox-box'),
                        $parent  = $(this).find('.checkbox-box:first'),
                        $checked = $all.filter('.lyear-dropdown-tree-checked');
                    
                    if ($all.length == $checked.length) {
                        $configs.relationChildren && $parent.removeClass('lyear-dropdown-tree-half-checked').addClass('lyear-dropdown-tree-checked');
                    } else if ($checked.length == 0) {
                        if ($parent.hasClass('lyear-dropdown-tree-half-checked')) {
                            $parent.removeClass('lyear-dropdown-tree-half-checked')
                        }
                        $configs.relationChildren && $parent.removeClass('lyear-dropdown-tree-checked');
                    } else {
                        if (!$parent.hasClass('lyear-dropdown-tree-checked')) {
                            $parent.addClass('lyear-dropdown-tree-half-checked');
                        }
                        //$configs.relationChildren && $parent.removeClass('lyear-dropdown-tree-checked').addClass('lyear-dropdown-tree-half-checked');
                    }
                });
                
                if ($configs.checkHandler) $configs.checkHandler($(this).parent("li"));
                
                $thisLyearDropdownTree.$element.find('input[type="text"]').val($thisLyearDropdownTree.getSelectedText());
            });
            
            $currenTree.on('click', '.radio-box', function(e) {
                $thisLyearDropdownTree.$element.find('.radio-box')
                    .removeClass('lyear-dropdown-tree-half-checked')
                    .removeClass('lyear-dropdown-tree-checked');
                $(this).addClass("lyear-dropdown-tree-checked");
                
                $(this).parent().parent().parents('li').each(function() {
                    var $all     = $(this).find('ul:first').find('.radio-box'),
                        $parent  = $(this).find('.radio-box:first')
                        $checked = $all.filter('.lyear-dropdown-tree-checked');
                    
                    if ($checked.length == 0) {
                        $parent.removeClass('lyear-dropdown-tree-half-checked').removeClass('lyear-dropdown-tree-checked');
                    } else {
                        $parent.removeClass('lyear-dropdown-tree-checked').addClass('lyear-dropdown-tree-half-checked')
                    }
                });
                
                if ($configs.checkHandler) $configs.checkHandler($(this).parent("li"));
                
                $thisLyearDropdownTree.$element.find('input[type="text"]').val($thisLyearDropdownTree.getSelectedText());
            });
        },
        
        // 设置选中项，如果是单选，则只设置最后一个为选中
        setSelectedItem : function(idArr) {
            if (Array.isArray(idArr) != true && idArr.length == 0) return false;
            
            if (this.configs.multiSelect == true) {
                for (var i = 0; i < idArr.length; i++) {
                    var tempNode = this.$element.find('a[data-id="'+idArr[i]+'"]').prev('i');
                    if (!tempNode.hasClass('lyear-dropdown-tree-checked')) {
                        tempNode.click();
                    }
                }
            } else {
                var idArrCount = idArr.length;
                this.$element.find('a[data-id="'+idArr[idArrCount-1]+'"]').prev('i').click();
            }
            
            this.$element.find('input[type="text"]').val(this.getSelectedText());
        },
        refreshTree:function(){
	        this.$element.find(".lyear-dropdown-tree-checked").removeClass("lyear-dropdown-tree-checked");
            this.$element.find(".lyear-dropdown-tree-half-checked").removeClass("lyear-dropdown-tree-half-checked");
	        this.$element.find('input[type="text"]').val("");
        },
        // 获取选中项文字
        getSelectedText : function() {
            var selectedElements = [];
            this.$element.find(".lyear-dropdown-tree-checked").each(function(){
                selectedElements.push($(this).next('a').text());
            });
            
            return selectedElements.join(this.configs.jsonStr);
        },
                
        // 获取选中项ID
        getSelectedID : function() {
            var selectedElements = [];
            this.$element.find(".lyear-dropdown-tree-checked").each(function(){
                selectedElements.push($(this).next('a').data('id'));
            });
            
            return selectedElements.join(this.configs.jsonStr);
        },
    };

    $.fn.lyearDropdownTree = function(options) {
        var obj = new lyearDropdownTree(this, options);
        
        return obj.init();
    }    
})(jQuery, window, document);