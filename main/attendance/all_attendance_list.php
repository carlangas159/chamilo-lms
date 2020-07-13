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
    echo '<div class="actions">////';
    echo '<a href="index.php?'.api_get_cidreq().'&action=attendance_add">'.
        Display::return_icon('new_attendance_list.png', get_lang('CreateANewAttendance'), '', ICON_SIZE_MEDIUM).'</a>';
    echo '</div>';
}
$student_id = (int)$_GET['student_id'];

$startDate = new DateTime();
if(isset($_GET['startDate'])){
    $startDate = new DateTime($_GET['startDate']);

}
$startDate = $startDate->setTime(0,0,0);

$endDate =  new DateTime();
$endDate = $endDate->modify('-1 week');
if(isset($_GET['endDate'])){
    $endDate = new DateTime($_GET['endDate']);

}
$endDate = $endDate->setTime(23,59,0);


$attendance_id = 0;
$calendar_id = 0;
//index2.php?cidReq=3268&origin=&action=all_attendance_sheet_list&=1
//http://chamilo.local.com/main/attendance/index2.php?startDate=2020-07-01+00%3A00&endDate=2020-07-10+00%3A00&submit=&_qf__attendance_calendar_edit=
//http://chamilo.local.com/main/attendance/index2.php?startDate=2020-07-01+00%3A00&endDate=2020-07-31+00%3A00&submit=&_qf__attendance_calendar_edit=
$form = new FormValidator(
    'attendance_calendar_edit',
    'GET',
    'index2.php?id_session=0&gidReq=0&gradebook=0&origin=&action=all_attendance_sheet_list&attendance_id='.$attendance_id.'&student_id='.$_GET['student_id'].'&&'.api_get_cidreq(),
    ''
);
//cidReq='.$_GET['cidReq'].'&
$today = new DateTime();
$today = $today->format('Y-m-d');
$defaults['startDate'] =$startDate->format('Y-m-d H:i:s');
$defaults['endDate'] = $endDate->format('Y-m-d H:i:s');
$defaults['action'] = 'all_attendance_sheet_list';
$form->addElement('html', '<input type="hidden" name="action" value="all_attendance_sheet_list" >');
$form->addElement('html', '<input type="hidden" name="student_id" value="'.$student_id.'" >');

$form->addDateTimePicker(
    'startDate',
    [get_lang('Date')],
    ['form_name' => 'attendance_calendar_edit'],
    5
);
$form->addDateTimePicker(
    'endDate',
    [get_lang('Date')],
    ['form_name' => 'attendance_calendar_edit'],
    5
);

// http://chamilo.local.com/main/attendance/
// $defaults['date_time'] = $calendar['date_time'];
$form->addButtonSave(get_lang('Save'));
$form->setDefaults($defaults);
$form->display();


$attendance = new Attendance();


if ($attendance->getNumberOfAttendances() == 0) {
    $attendance->set_name(get_lang('Attendances'));
    $attendance->set_description(get_lang('Attendances'));
    $attendance->attendance_add();
}
$default_column = isset($default_column) ? $default_column : null;
$parameters = isset($parameters) ? $parameters : null;

$attendance = new Attendance();

$pagination = 10;
$data =  $attendance->getCoursesWithAttendance($student_id,$startDate,$endDate);
$con_curso = 0;

if($con_curso == 1) {

    /*** Cursos Sin categoria */

    $procesar = $data['not_category'];
    $enviar = [];
    $tables = [];

    foreach ($procesar as $k => $v) {
        foreach ($v['attendanceSheet'] as $k1 => $v1) {
            foreach ($v1 as $k2 => $v2) {
                foreach ($v2 as $k3 => $v3) {
                    $enviar[] = $v3;
                }
                if(count($enviar) != 0) {
                    $table = new SortableTableFromArrayConfig(
                        $enviar, 0, 50, 'not_category' . $v['course_id'], [
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
    for ($i = 0; $i < count($tables); $i++) {
        $t = $tables[$i];
        $t->display();

    }
    /*** Cursos en categoria */
    $procesar = $data['in_category'];
    $enviar = [];
    $tables = [];

    foreach ($procesar as $k => $v) {
        foreach ($v['attendanceSheet'] as $k1 => $v1) {
            foreach ($v1 as $k2 => $v2) {
                foreach ($v2 as $k3 => $v3) {
                    $enviar[] = $v3;
                }

                if(count($enviar) != 0) {
                    $table = new SortableTableFromArrayConfig(
                        $enviar, 0, 50, 'in_category' . $v['course_id'], [
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
    for ($i = 0; $i < count($tables); $i++) {
        $t = $tables[$i];
        $t->display();

    }
}else{
    /***  Agrupado por fecha */
    /*
    usort($data, function($a, $b) {
        return ($a['date'] < $b['date']) ? -1 : 1;
    });
    */

    $procesar = $data;
    $enviar = [];
    $tables = [];

    foreach ($procesar as $k => $v) {
        $enviar = $v;
        $table = new SortableTableFromArrayConfig(
            $enviar, 0, 50, 'date_' . $k, [
                'presence', 'date_time', 'courseTitle',
            ]
        );
        $table->set_additional_parameters($parameters);
        $table->set_header('courseTitle', get_lang('Training'), false);
        $table->set_header('date_time', get_lang('DateExo'), false);
        $table->set_header('presence', get_lang('Present'), false);
        $tables[] = $table;
    }
    for ($i = 0; $i < count($tables); $i++) {
        $t = $tables[$i];
        $t->display();

    }
}
