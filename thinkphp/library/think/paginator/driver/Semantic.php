<?php
/**
 * Created by alic(AlicFeng) on 17-6-15 下午9:17 from PhpStorm.
 * Email is alic@samego.com
 */

namespace think\paginator\driver;


use think\Paginator;

class Semantic extends Paginator
{
    private static $previousButtonHtml = '<i class="icon left arrow"></i>';
    private static $nextButtonHtml = '<i class="icon right arrow"></i>';
    private static $extendHtml = '<div class="ui small form" style="display: inline-block;margin-left: 8px"><div class="inline fields"><div class="field"><select class="ui dropdown compact" name="page_num" id="page_num"><option value="10">10条/页</option><option value="20">20条/页</option><option value="30">30条/页</option><option value="40">40条/页</option></select></div><label>跳至</label><div class="three wide field"><input type="text" name="page_goto" id="page_goto"></div></div></div>';

    /**
     * 上一页按钮
     * @return string
     */
    protected function getPreviousButton()
    {

        if ($this->currentPage() <= 1) {
            return $this->getDisabledTextWrapper(Semantic::$previousButtonHtml);
        }

        $url = $this->url(
            $this->currentPage() - 1
        );

        return $this->getPageLinkWrapper($url, Semantic::$previousButtonHtml);
    }

    /**
     * 下一页按钮
     * @return string
     */
    protected function getNextButton()
    {
        if (!$this->hasMore) {
            return $this->getDisabledTextWrapper(Semantic::$nextButtonHtml);
        }

        $url = $this->url($this->currentPage() + 1);

        return $this->getPageLinkWrapper($url, Semantic::$nextButtonHtml);
    }

    /**
     * 扩展分页
     * @return string
     */
    protected function getExtendHtml()
    {
        return Semantic::$extendHtml;
    }
    /**
     * 页码按钮
     * @return string
     */
    protected function getLinks()
    {
        $block = [
            'first' => null,
            'slider' => null,
            'last' => null
        ];

        $side = 3;
        $window = $side * 2;

        if ($this->lastPage < $window + 6) {
            $block['first'] = $this->getUrlRange(1, $this->lastPage);
        } elseif ($this->currentPage <= $window) {
            $block['first'] = $this->getUrlRange(1, $window + 2);
            $block['last'] = $this->getUrlRange($this->lastPage - 1, $this->lastPage);
        } elseif ($this->currentPage > ($this->lastPage - $window)) {
            $block['first'] = $this->getUrlRange(1, 2);
            $block['last'] = $this->getUrlRange($this->lastPage - ($window + 2), $this->lastPage);
        } else {
            $block['first'] = $this->getUrlRange(1, 2);
            $block['slider'] = $this->getUrlRange($this->currentPage - $side, $this->currentPage + $side);
            $block['last'] = $this->getUrlRange($this->lastPage - 1, $this->lastPage);
        }

        $html = '';

        if (is_array($block['first'])) {
            $html .= $this->getUrlLinks($block['first']);
        }

        if (is_array($block['slider'])) {
            $html .= $this->getDots();
            $html .= $this->getUrlLinks($block['slider']);
        }

        if (is_array($block['last'])) {
            $html .= $this->getDots();
            $html .= $this->getUrlLinks($block['last']);
        }

        return $html;
    }

    /**
     * 渲染分页html
     * @return mixed
     */
    public function render()
    {
        if ($this->hasPages()) {
            if ($this->simple) {
                return sprintf(
                    '<div class="ui pagination menu mini">%s %s</div>',
                    $this->getPreviousButton(),
                    $this->getNextButton()
                );
            } else {
                $page_list = sprintf(
                    '<div class="ui pagination menu mini">%s %s %s</div>',
                    $this->getPreviousButton(),
                    $this->getLinks(),
                    $this->getNextButton()
                );
                return $page_list . $this->getExtendHtml();
            }
        }
        return null;
    }

    /**
     * 生成一个可点击的按钮
     *
     * @param  string $url
     * @param  int $page
     * @return string
     */
    protected function getAvailablePageWrapper($url, $page)
    {
        return '<a href="' . htmlentities($url) . '" class="item">' . $page . '</a>';
    }

    /**
     * 生成一个禁用的按钮
     *
     * @param  string $text
     * @return string
     */
    protected function getDisabledTextWrapper($text)
    {
        return '<a class="disabled item">' . $text . '</a>';
    }

    /**
     * 生成一个激活的按钮
     *
     * @param  string $text
     * @return string
     */
    protected function getActivePageWrapper($text)
    {
        return '<a class="active item">' . $text . '</a>';
    }

    /**
     * 生成省略号按钮
     *
     * @return string
     */
    protected function getDots()
    {
        return $this->getDisabledTextWrapper('...');
    }

    /**
     * 批量生成页码按钮.
     *
     * @param  array $urls
     * @return string
     */
    protected function getUrlLinks(array $urls)
    {
        $html = '';
        foreach ($urls as $page => $url) {
            $html .= $this->getPageLinkWrapper($url, $page);
        }

        return $html;
    }

    /**
     * 生成普通页码按钮
     *
     * @param  string $url
     * @param  int $page
     * @return string
     */
    protected function getPageLinkWrapper($url, $page)
    {
        if ($page == $this->currentPage()) {
            return $this->getActivePageWrapper($page);
        }

        return $this->getAvailablePageWrapper($url, $page);
    }

}