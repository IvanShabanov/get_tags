<?


function get_tags($tag, $content, $haveClosedTag = true) {
    if ($haveClosedTag) {
        $arTag['tag'] = '/(<'.$tag.'[^>]*>)(.*)<\/'.$tag.'>/ismuU';;
    } else {
        $arTag['tag'] = '/(<'.$tag.'[^>]*>)/ismuU';
    };
    $arTag['attr'][0] = '/\s+([a-zA-Z-]+)\s*=\s*"([^"]*)"/ismuU';
    $arTag['attr'][] = str_replace('"', "'", $arTag['attr'][0]);
    $result = array();
    if (preg_match_all($arTag['tag'], $content, $matches)) {
        foreach ($matches[0] as $k=>$match) {
            $res_tag = array();
            $res_tag['tag'] = $match;
            if (isset($matches[1][$k]))  {
                foreach ($arTag['attr'] as $arTagAttr) {
                    unset($attr_matches);
                    preg_match_all($arTagAttr, $matches[1][$k], $attr_matches);
                    if (is_array($attr_matches[1])) {
                        foreach ($attr_matches[1] as $key=>$val) {
                            $res_tag[$val] = $attr_matches[2][$key];
                        };
                    };
                };
            };
            if (isset($matches[2][$k])) {
                $res_tag['text'] = $matches[2][$k];
            };
            $result[] = $res_tag;
        };
    };
    return $result;
}