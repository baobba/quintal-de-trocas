<?php

	echo '
		<div class="message ' . $kind . ' ' . ($can_close == true ? 'close' : '') . '">
			<h2>'. $title . '</h2>
			<p>' . $message . '</p>
		</div>'
	;

?>