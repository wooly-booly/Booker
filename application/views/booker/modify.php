<?php
    if (!empty($errors)) {
        foreach ($errors as $err) {
            echo '<b class="error">'.$err.'</b><br/>';
        }
    } elseif (!empty($success)) {
        echo '<b class="success">'.$success.'</b><br/>';
    }
?>

<p><a href="/booker/index">&lt;&lt; Main page</a></p>
