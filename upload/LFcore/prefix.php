<?
$pr_t_sum = msres(dbquery("SELECT COUNT('prefix_id') FROM forum_them_prefix WHERE them_id = " . $a['id'] . ""), 0);
$prefixes = dbquery("SELECT * FROM `forum_them_prefix` WHERE `them_id`='" . $a['id'] . "' ORDER BY `id` DESC LIMIT 1");
while ($pr = mfa($prefixes)) {
    $prefix_them = dbquery("SELECT * FROM `forum_prefix` WHERE `id`='" . $pr['prefix_id'] . "' ORDER BY `id` DESC");
    while ($pr_t = mfa($prefix_them)) {
        if ($pr_t_sum == 1) {
            echo '<span class="prefix_for_them" style="' . $pr_t['style'] . '">' . $pr_t['name'] . '</span>';
        } else if ($pr_t_sum > 2) {
            echo '<span class="prefix_for_them2" style="' . $pr_t['style'] . '">' . $pr_t['name'] . '</span><span class="prefix2_plusone">+2</span>';
        } else if ($pr_t_sum > 1) {
            echo '<span class="prefix_for_them2" style="' . $pr_t['style'] . '">' . $pr_t['name'] . '</span><span class="prefix2_plusone">+1</span>';
        }
    }
}
