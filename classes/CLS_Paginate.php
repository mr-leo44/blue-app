<?php
class CLS_Paginate
{
    //Lorqu'on fait le controle recuperer le dernier ref_install et stocker pour le control for reporting 
    // database connection and table name
    public $result;
    public $page = 0;
    public $total_rows = 0;
    public $records_per_page = 0;
    public $range_ = 0;
    public $page_url = '';
    // public $view_mode = ''; 


    public function Paginate($view_mode)
    {

        // $this->view_mode = $view_mode;
        $this->result = "";
        $this->result .= "<ul class=\"pagination\">";

        // button for first page
        $href = $this->page <= 1 ? '0' : '1';
        $disabled = $this->page <= 1 ? 'disabled' : '';
        $this->result .= '<li class="page-item ' . $disabled . '" ><a   href="#" data-page="' . $href . '" class="page-link" view-mode="' . $view_mode . '"><i class="fa fa-angle-double-left"></i></a></li>';
        // count all products in the database to calculate total pages
        $total_pages = ceil($this->total_rows / $this->records_per_page);
        $half = ceil($this->range_ / 2);

        $numbers['start'] = 0;
        $numbers['end'] = 0;
        // Prev + Next
        $prev = $this->page - 1;
        $next = $this->page + 1;
        // display links to 'range of pages' around 'current page'
        $initial_num = $this->page - $this->range_;
        $condition_limit_num = ($this->page + $this->range_)  + 1;
        $href_js = 'href="javascript:void(0);"';
        for ($x = $initial_num; $x < $condition_limit_num; $x++) {

            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
            if (($x > 0) && ($x <= $total_pages)) {

                // current page
                if ($x == $this->page) {
                    //  echo "<span class='customBtn' style='background:#0D6598;'>$x</span>";
                    $this->result .= "<li class='page-item active'><a class='page-link'  href='#' data-page='$x'  view-mode='" . $view_mode . "'>$x</a></li>";
                }

                // not current page
                else {
                    // echo " <a href='{$_SERVER['PHP_SELF']}?page=$x' class='customBtn'>$x</a> ";
                    //echo "<li class='page-item' ><a class='page-link' href='{$page_url}page=$x'>$x</a></li>";
                    $this->result .= "<li class='page-item' ><a class='page-link' href='#' data-page='$x'  view-mode='" . $view_mode . "'>$x</a></li>";
                }
            }
        }

        //$href=$this->page == $total_pages ? '#':$this->page_url.'page='.$total_pages;
        $href = $total_pages;
        $disabled = $this->page == $total_pages ? 'disabled' : '';

        $this->result .= '<li class="page-item ' . $disabled . '" ><a   href="#" data-page="' . $href . '" class="page-link"  view-mode="' . $view_mode . '"><i class="fa fa-angle-double-right"></i></a></li>';

        $this->result .= "</ul>";

        return $this->result;
    }
}
