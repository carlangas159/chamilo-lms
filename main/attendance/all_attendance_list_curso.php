<?php
/* For licensing terms, see /license.txt */

/**
 * View (MVC patter) for listing attendances.
 *
 * @author Christian Fasanando <christian1827@gmail.com>
 *
 * @package chamilo.attendance
 */

// protect a course script
api_protect_course_script(true);

if (api_is_allowed_to_edit(null, true)) {
    echo '<div class="actions">*******';
    echo '<a href="index.php?'.api_get_cidreq().'&action=attendance_add">'.
        Display::return_icon('new_attendance_list.png', get_lang('CreateANewAttendance'), '', ICON_SIZE_MEDIUM).'</a>';
    echo '</div>';
}


$attendance = new Attendance();


if ($attendance->getNumberOfAttendances() == 0) {
    $attendance->set_name(get_lang('Attendances'));
    $attendance->set_description(get_lang('Attendances'));
    $attendance->attendance_add();
}
$default_column = isset($default_column) ? $default_column : null;
$parameters = isset($parameters) ? $parameters : null;

$student_id = (int)$_GET['student_id'];
$attendance = new Attendance();

$startDate = null;
if(isset($_GET['startDate'])){
    $startDate = new DateTime('2020-07-05 00:00:00');

}
$endDate = null;
if(isset($_GET['endDate'])){
    $endDate = new DateTime('2020-07-07 23:59:00');

}
$pagination = 10;
$data =  $attendance->getCoursesWithAttendance($student_id,$startDate,$endDate);
/*** Cursos Sin categoria */

$procesar = $data['not_category'];
$enviar = [];
$tables = [];

foreach($procesar as $k=>$v){
    foreach ($v['attendanceSheet'] as $k1 => $v1) {
        foreach ($v1 as $k2 => $v2) {
            foreach ($v2 as $k3 => $v3) {
                $enviar[] = $v3;
            }
                if(count($enviar)!=0) {
                    $table = new SortableTableFromArrayConfig(
                        $enviar, 0, 50, 'not_category'.$v['course_id'], [
                            'presence', 'date_time', 'courseTitle',
                        ]
                    );
                    $table->set_additional_parameters($parameters);
                    $table->set_header('presence', get_lang('Present'), false);
                    $table->set_header('date_time', get_lang('DateExo'), false);
                    $table->set_header('courseTitle', get_lang('Training'), false);
                    $tables[] = $table;
                }
        }
    }
}
for($i = 0;$i<count($tables);$i++){
    $t = $tables[$i];
    $t->display();

}
/*** Cursos en categoria */
$procesar = $data['in_category'];
$enviar = [];
$tables = [];

foreach($procesar as $k=>$v){
    foreach ($v['attendanceSheet'] as $k1 => $v1) {
        foreach ($v1 as $k2 => $v2) {
            foreach ($v2 as $k3 => $v3) {
                $enviar[] = $v3;
            }

            if(count($enviar)!=0) {
                $table = new SortableTableFromArrayConfig(
                    $enviar, 0, 50, 'in_category'.$v['course_id'], [
                        'presence', 'date_time', 'courseTitle',
                    ]
                );
                $table->set_additional_parameters($parameters);
                $table->set_header('presence', get_lang('Present'), false);
                $table->set_header('date_time', get_lang('DateExo'), false);
                $table->set_header('courseTitle', get_lang('Training'), false);
                $tables[] = $table;
            }
        }
    }
}
for($i = 0;$i<count($tables);$i++){
    $t = $tables[$i];
    $t->display();

}
