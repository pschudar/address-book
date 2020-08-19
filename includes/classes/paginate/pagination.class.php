<?php

/**
 * A reusable pagination class to display page links for content
 */

namespace paginate;

class Pagination {

    public $current_page;
    public $per_page;
    public $total_count;

    /**
     * __construct
     * 
     * Sets defaults and casts needed variables to integers to allow for
     * mathematical calculations to be performed on them.
     * 
     * @param int $page
     * @param int $per_page
     * @param int $total_count
     */
    public function __construct($page = 1, $per_page = 10, $total_count = 0) {
        $this->current_page = (int) $page;
        $this->per_page = (int) $per_page;
        $this->total_count = (int) $total_count;
    }

    /**
     * Applies the formula for offset
     */
    public function offset() {
        return $this->per_page * ($this->current_page - 1);
    }

    /**
     *  Determines the total number of pages. Always Rounds Up (ceil())
     * @return int
     */
    public function totalPages() {
        return ceil($this->total_count / $this->per_page);
    }

    /**
     * Determines if a previous page exists
     * 
     * if $prev is greater than 0, returns the value of current_page - 1. 
     * Otherwise, the method returns false indicating no need for a previous link
     * For example, ?page=1 does not require a previous link, so no need for the output.
     * @return int || boolean
     */
    public function previousPage() {
        $prev = $this->current_page - 1;
        return ($prev > 0) ? $prev : false;
    }

    /**
     * Determines if a next page exists
     * 
     * 
     * @return int || boolean
     */
    public function nextPage() {
        $next = $this->current_page + 1;
        return ($next <= $this->totalPages()) ? $next : false;
    }

    public function previousLink($url = '') {
        $link = '';
        if ($this->previousPage() != false) {
            $link .= "<a class=\"w3-button\" href=\"{$url}?page={$this->previousPage()}\">";
            $link .= "&laquo; Previous</a>";
        }
        return $link;
    }

    public function nextLink($url = "") {
        $link = '';
        if ($this->nextPage() != false) {
            $link .= "<a class=\"w3-button\" href=\"{$url}?page={$this->nextPage()}\">";
            $link .= "Next &raquo;</a>";
        }
        return $link;
    }

    public function numberLinks($url = '', $numLinksEitherSide = 7) {
        $output = '';
        for($i = max(1, $this->current_page - $numLinksEitherSide); $i <= min($this->current_page + $numLinksEitherSide, $this->totalPages()); $i++) {
            if ($i == $this->current_page) {
                $output .= "<a id='currentPageLink' class='w3-button w3-border w3-theme-d3' style=\"pointer-events: none;\" tabindex=\"-1\">{$i}</a> <span class=\"sr-only\">(current)</span>";
            } else {
                $output .= "<a class=\"w3-button\" href=\"{$url}?page={$i}\">{$i}</a>";
            }
        }
        return $output;
    }

    public function pageLinks($url) {
        $output = '';
        if ($this->totalPages() > 1) {
            $output .="<nav aria-label='Pagination Links'>";
            $output .="<div class='w3-center'>";
            $output .= "<div id='w3-pagination' class='w3-bar'>";
            $output .= $this->previousLink($url);
            $output .= $this->numberLinks($url);
            $output .= $this->nextLink($url);
            $output .= "</div>";
            $output .="</div>";
            $output .='</nav>';
        }
        return $output;
    }

}
