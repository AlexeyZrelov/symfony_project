<?php

namespace App\Utils;

use App\Utils\AbstractClasses\CategoryTreeAbstract;

class CategoryTreeAdminList extends CategoryTreeAbstract
{
    public string $html_1 = '<ul class="fa-ul text-left">';
    public string $html_2 = '<li><i class="fa-li fa fa-arrow-right"></i>';
    public string $html_3 = '<a href="';
    public string $html_4 = '">';
    public string $html_5 = '</a> <a onclick="return confirm(\'Are you sure?\');" href="';
    public string $html_6 = '">';
    public string $html_7 = '</a>';
    public string $html_8 = '</li>';
    public string $html_9 = '</ul>';

    public function getCategoryList(array $categories_array): string
    {

        $this->categorylist .= $this->html_1;

        foreach ($categories_array as $value)
        {
            $url_edit = $this->urlGenerator->generate('edit_category', ['id'=>$value['id']]);
            $url_delete = $this->urlGenerator->generate('delete_category', ['id'=>$value['id']]);

            $this->categorylist .= $this->html_2 . $value['name'] . $this->html_3 . $url_edit . $this->html_4 . 'Edit' . $this->html_5 . $url_delete . $this->html_6 . 'Delete' . $this->html_7;

            if (!empty($value['children'])) {
                $this->getCategoryList($value['children']);
            }

            $this->categorylist .= $this->html_8;
        }

        $this->categorylist .= $this->html_9;

        return $this->categorylist;
    }
}