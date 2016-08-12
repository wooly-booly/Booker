<script src="/public/js/confirmDelete.js"></script>
<table>
<?php
    if (!empty($employees)) {
        foreach ($employees as $employee) {
            echo '<tr>';
            echo "<td width='200px'><a href='mailto:".$employee->email."'>".$employee->name.'</a></td>';
            echo "<td width='100px'><a class='confirmDel' id='1' href='/employee/delete/id=".$employee->id."'>REMOVE</a></td>";
            echo "<td width='100px'><a href='/employee/edit/id=".$employee->id."'>EDIT</a></td>";
            echo '</tr>';
        }
    }
?>
</table>

<br/>
<p><a href="/employee/add">Add a new employee</a></p>
