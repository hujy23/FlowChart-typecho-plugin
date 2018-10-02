<?php
/**
 * 流程图
 * 
 * @package FlowChart 
 * @author ZhangZijing(Venteto)
 * @version 1.2.1
 * @link https://v.meloduet.com
 */
class FlowChart_Plugin implements Typecho_Plugin_Interface {
     /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        Typecho_Plugin::factory('Widget_Archive')->header = array(__CLASS__, 'header');       
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){

        $lang = new Typecho_Widget_Helper_Form_Element_Radio('lang', array(
            0   =>  _t('英文'),
            1   =>  _t('中文')
        ), 1, _t('语言'), _t('判断语句显示为Yes, No还是是, 否'));
        $form->addInput($lang->addRule('enum', _t('请选择一种语言'), array(0, 1)));

        $reverse = new Typecho_Widget_Helper_Form_Element_Radio('reverse', array(
            0   =>  _t('否'),
            1   =>  _t('是')
        ), 0, _t('反转颜色'), _t('用于暗色主题'));
        $form->addInput($reverse->addRule('enum', _t('请选择一种'), array(0, 1)));

		$importJquery = new Typecho_Widget_Helper_Form_Element_Radio('importJquery', array(
            0   =>  _t('否'),
            1   =>  _t('是')
        ), 1, _t('引入jQuery'), _t('本插件需要jQuery, 如果你的网站没有自带jQuery, 请选择"是"'));
        $form->addInput($importJquery->addRule('enum', _t('请选择一个'), array(0, 1)));


    }

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render() {
        
    }

   
    /**
     * 判断是否是内容页，避免主页加载插件
     */
    public static function is_content() {
        static $is_content = null;
        if($is_content === null) {
            $widget = Typecho_Widget::widget('Widget_Archive');
            $is_content = !($widget->is('index') || $widget->is('search') || $widget->is('date') || $widget->is('category') || $widget->is('author'));
        }
        return $is_content;
    }
 

    /**
     *为header添加css文件
     *@return void
     */
    public static function header() {
        /*if (!self::is_content()) {
            return;
        }*/
        $yesText = "是";
        $noText = "否";
        if (!Helper::options()->plugin('FlowChart')->lang) {
            $yesText = "Yes";
            $noText = "No";
        }
$color="black";
$fill="white";
        if (Helper::options()->plugin('FlowChart')->reverse) {
            $color="white";
            $fill="transparent";
        }
		if (Helper::options()->plugin('FlowChart')->importJquery) {
            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
        }
         echo <<<HTML
         
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowchart/1.11.0/flowchart.min.js"></script>
<script>
        $(function () {

            var flow_elements = $('code.language-flow,code.lang-flow');
			console.log(flow_elements.length);
			for(var i =0; i<flow_elements.length; i++)
			{
				var flow_element = flow_elements[i];
				var container =document.createElement("div");
				console.log(flow_element);
				flow_element.parentNode.parentNode.insertBefore(container,flow_element.parentNode);
				var code = flow_element.innerText;
				chart = flowchart.parse(code);
				flow_element.parentNode.remove();
				chart.drawSVG(container, {
                              'x': 0,
                              'y': 0,
                              'line-width': 1,
                              'line-length': 50,
                              'text-margin': 12,
                              'font-size': 14,
                              'font-color': '$color',
                              'line-color': '$color',
                              'element-color': '$color',
                              'fill': '$fill',
                              'yes-text': '$yesText',
                              'no-text': '$noText',
                              'arrow-end': 'block',
                              'scale': 1
                              ,
                            });
			}

        });
            
    </script>


HTML;
        
    }


}
