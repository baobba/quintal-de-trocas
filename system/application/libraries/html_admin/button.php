<?php

class button {

    CONST ICON_TRASH 	 = 'ui-icon-trash';
    const ICON_PENCIL 	 = 'ui-icon-pencil';
    const ICON_IMAGE 	 = 'ui-icon-image';
    const ICON_ZOOM = 'ui-icon-zoomin';
    
	public static function render($icon = '', $buttonUrl = '', $title = null, $txt = null, $confirm = null, $target = null)
	{
	    $target = $target ? "target=\"$target\"" : '';
	    
	    return '
	    	<a ' . $target . ' href="' . $buttonUrl . '"  class="button tooltip" onclick="' . ($confirm ? 'return confirm(\'' . $confirm . '\');' : '' ) . '" style="' . (!$txt ? 'padding-right:0' : '') . '" title="' . $title . '">
				<span class="ui-icon ' . $icon . '"></span> ' . $txt . '
	    	</a>';
	}
}