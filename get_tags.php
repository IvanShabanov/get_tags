<?
function get_tags($tag, $content, $haveClosedTag = true)
{
    preg_match_all('/^([a-zA-Z]+)/', $tag, $seletorTag);
    preg_match_all('/#([a-zA-Z0-9-_]+)*/', $tag, $seletorIds);
    preg_match_all('/\.([a-zA-Z0-9-_]+)*/', $tag, $seletorClass);
    preg_match_all('/\[(.*)\]/', $tag, $seletorParams);
    if (!empty($seletorParams[1][0])) {
        $strParams = ' ' . str_replace(',', ' ', $seletorParams[1][0]);
        preg_match_all('/\s+([a-zA-Z-]+)\s*=\s*"([^"]*)"/ismuU', $strParams, $seletorParams);
    } else {
        $seletorParams = [];
    }
    if (!empty($seletorTag[1][0])) {
        $tag = $seletorTag[1][0];
    } else {
        $tag = '';
    }
    $arFilter = [];
    if (!empty($seletorIds[1])) {
        $arFilter['id'] = $seletorIds[1];
    };
    if (!empty($seletorClass[1])) {
        $arFilter['class'] = $seletorClass[1];
    };
    if (is_array($seletorParams[1])) {
        foreach ($seletorParams[1] as $key => $val) {
            $arFilter[$val][] = $seletorParams[2][$key];
        };
    };
    if ($tag == '') {
        return;
    }

    $notClosedTags = [
        'araa',
        'base',
        'br',
        'col',
        'command',
        'embed',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr',
    ];

    if (!in_array($tag, $notClosedTags) && $haveClosedTag) {
        $arTag['tag'] = '/(<' . $tag . '[^>]*>)(.*)<\/' . $tag . '>/ismuU';;
    } else {
        $arTag['tag'] = '/(<' . $tag . '[^>]*>)/ismuU';
    };

    $arTag['attr'][0] = '/\s+([a-zA-Z-]+)\s*=\s*"([^"]*)"/ismuU';
    $arTag['attr'][] = str_replace('"', "'", $arTag['attr'][0]);
    $result = [];
    if (preg_match_all($arTag['tag'], $content, $matches)) {
        foreach ($matches[0] as $k => $match) {
            $res_tag = [];
            $res_tag['tag'] = $match;
            if (isset($matches[1][$k])) {
                foreach ($arTag['attr'] as $arTagAttr) {
                    unset($attr_matches);
                    preg_match_all($arTagAttr, $matches[1][$k], $attr_matches);
                    if (is_array($attr_matches[1])) {
                        foreach ($attr_matches[1] as $key => $val) {
                            $res_tag[$val] = $attr_matches[2][$key];
                        };
                    };
                };
            };
            if (isset($matches[2][$k])) {
                $res_tag['text'] = $matches[2][$k];
            };
            $ok = true;
            if (!empty($arFilter)) {
                foreach ($arFilter as $attrkey => $arValues) {
                    if (!isset($res_tag[$attrkey])) {
                        $ok = false;
                        break;
                    }
                    if (!is_array($arValues)) {
                        continue;
                    }
                    $arCurValues = explode(' ', $res_tag[$attrkey]);
                    foreach ($arValues as $searchValue) {
                        if (!in_array($searchValue, $arCurValues)) {
                            $ok = false;
                            break 2;
                        }
                    }
                }
            }
            if ($ok) {
                $result[] = $res_tag;
            }
        };
    };
    return $result;
}
