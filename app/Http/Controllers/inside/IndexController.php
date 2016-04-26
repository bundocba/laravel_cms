<?php

namespace App\Http\Controllers\Inside;

use App\Http\Controllers\inside\BaseController;
use App\Http\Models\inside\User;
use App\Http\Models\inside\UserGroup;
use App\Http\Models\inside\Post;
use App\Http\Models\inside\PostCategory;

class IndexController extends BaseController {

    public function actionIndex() {

        /* * */
        /* * ********** Graph Infos ********** */
        /* * */
        $returnData = array();
        $curYear = date('Y');

        // Current year
        $data = Post::getAmountByMonth($curYear);
        $this->parseMonth($returnData, $data, $curYear);

        // CurYear - 1
        $data = Post::getAmountByMonth($curYear - 1);
        $this->parseMonth($returnData, $data, $curYear - 1);
                
        $highestMonthValue = 0;

        // Summary by year
        $summaryMonth[0] = $this->parse_to_string_by_year($returnData, $curYear, $highestMonthValue);
        $summaryMonth[1] = $this->parse_to_string_by_year($returnData, $curYear - 1, $highestMonthValue);

        $year1 = Post::getTotalAmountInYear($curYear);
        $year2 = Post::getTotalAmountInYear($curYear - 1);
        $summaryYear = $year1 + $year2;

        /* * */
        /* * ********** Other Infos ********** */
        /* * */
        $data = [];        

        $listNewestUsers = User::getNewestUsers();
        $listNewestPosts = Post::getNewestPosts();

        $data['listNewestUsers'] = $listNewestUsers;
        $data['listNewestPosts'] = $listNewestPosts;

        $data['totalUsers'] = User::getTotalUsers();
        $data['totalUserGroups'] = UserGroup::getTotalUserGroups();
        $data['totalPosts'] = Post::getTotalPosts();
        $data['totalPostCategories'] = PostCategory::getTotalPostCategories();
        
        $data['summaryYear'] = $summaryYear;
        $data['summaryMonth'] = $summaryMonth;
        $data['curYear'] = $curYear;

        return view('inside.index', $data);
    }

    protected function parse_to_string_by_year($result_data, $year, &$highestValue) {
        $summaryMonth = '[';

        for ($i = 1; $i < 13; $i++) {
            if (isset($result_data[$year]) && is_array($result_data[$year]) && isset($result_data[$year][$i])) {
                $data = $result_data[$year];

                if ($i == $data['highestMonth']) {
                    // Find the highestValue of 3 year, used for calculate_tickInterval
                    if ($data[$i] > $highestValue) {
                        $highestValue = $data[$i];
                    }

                    // Icon sun for highest month
                    $summaryMonth .= '{
                        y: ' . $data[$i] . ',
                        marker: {
                            symbol: "url(public/inside/assets/highcharts/img/sun.png)"
                        }
                    },';
                } elseif ($i == $data['lowestMonth']) {
                    // Icon snow for lowest month
                    $summaryMonth .= '{
                        y: ' . $data[$i] . ',
                        marker: {
                            symbol: "url(public/inside/assets/highcharts/img/snow.png)"
                        }
                    },';
                } else {
                    $summaryMonth .= $data[$i] . ',';
                }
            } else {
                $summaryMonth .= 0 . ',';
            }
        }
        $len = strlen($summaryMonth);
        $summaryMonth = substr($summaryMonth, 0, $len - 1);

        $summaryMonth .= ']';
        return $summaryMonth;
    }

    protected function parseMonth(&$result, $data, $year) {
        $temp = array();
        if (!empty($data)) {
            $temp['highestMonth'] = $data[0]['month'];
            $temp['lowestMonth'] = $data[0]['month'];
            $highest = $data[0]['total'];
            $lowest = $data[0]['total'];
            foreach ($data as $index => $row) {
                $month = $row['month'];
                $temp[$month] = $row['total'];
                if ($row['total'] > $highest) {
                    $temp['highestMonth'] = $month;
                    $highest = $row['total'];
                }
                if ($row['total'] < $lowest) {
                    $temp['lowestMonth'] = $month;
                    $lowest = $row['total'];
                }
            }
        }
        $result[$year] = $temp;
    }

}
