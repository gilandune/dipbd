<?php
try {
    
//error_reporting(0);
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    ob_start("ob_gzhandler"); 
} else {
    ob_start();
}

session_start();


if (!isset($_SESSION['idsys_user'])) {
    header('Location: /login/');
}

require_once dirname(__FILE__).'/general.sys.inc.php';

//$this_user = new User($_SESSION['idsys_user']);
//    
//$this_user->checkSession(session_id());

if (UNDER_MAINTENANCE) {
    header('Location: /logout.php');
}

$db = new DB('Franquicias');
$sp = "CALL SP_Orders({$_SESSION['idsys_user']});";
$res = $db->queryRow($sp);

$db2 = new DB('Franquicias');
$sp2 = "CALL SP_top_sales();";
$res2 = $db2->queryAll($sp2);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <base href="<?php echo BASE_URL; ?>" />
        <link rel="icon" href="images/sys/favicon.ico" sizes="16x16" type="image/ico" />
        <link rel="stylesheet" type="text/css" href="css/general.css"/>
        <link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.9.2.custom.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.ui-icons-colors.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.pnotify.default.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.pnotify.default.icons.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css"/>
        <link rel="stylesheet" type="text/css" href="css/ColVis.css"/>
        <link rel="stylesheet" type="text/css" href="css/fileuploader.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.treetable.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css"/>
        <link rel="stylesheet" type="text/css" href="css/ui-progress-bar.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery.jqplot.min.css"/>
        <title>Diplomado Base de Datos</title>
        <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
        <script type="text/javascript" src="js/jquery.pnotify.min.js"></script>
        <script type="text/javascript" src="js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="js/jquery.zclip.min.js"></script>
        <script type="text/javascript" src="js/jquery.treetable.js"></script>
        <script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>
        <script type="text/javascript" src="js/globalize.js"></script>
        <script type="text/javascript" src="js/cultures/globalize.cultures.js"></script>
        <script type='text/javascript' src='https://www.google.com/jsapi'></script>
        <script type="text/javascript" src="js/general.js"></script>
        
        <script class="include" type="text/javascript" src="js/jquery.jqplot.min.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.highlighter.min.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.cursor.min.js"></script>
        <script class="include" type="text/javascript" src="js/plugins/jqplot.barRenderer.min.js"></script>
        <script class="include" type="text/javascript" src="js/plugins/jqplot.categoryAxisRenderer.min.js"></script>
        <script class="include" type="text/javascript" src="js/plugins/jqplot.pointLabels.min.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.meterGaugeRenderer.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.canvasTextRenderer.min.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
        <script type="text/javascript" src="js/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
    </head>
    <body>
        <div id="maincontainer">
            <!-- header -->
            <div id="header">
                <h2>Diplomado - Administración de Bases de Datos</h2>
            </div>
            <!-- content -->
            <div id="content">
                <div id="tabs">
                    <ul>
                        <li>
                            <a href="#tabs-ordenes">
                                <span class="adm_submenu_title">
                                    Ordenes
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tabs-ventas">
                                <span class="adm_submenu_title">
                                    Ventas
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#tabs-imagenes">
                                <span class="adm_submenu_title">
                                    Respuestas
                                </span>
                            </a>
                        </li>
                    </ul>

                    <div id="tabs-ordenes">
                        <table id="orders_table" class="altertable">
                            <thead>
                                <th>cliente</th>
                                <th>ordenes recibidas</th>
                                <th>ordenes enviadas</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $res['cno']; ?></td>
                                    <td><?php echo $res['received']; ?></td>
                                    <td><?php echo $res['shipped']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <br/>
                        <div id="jqplot_global_id_cop_bars" style="height:300px; width:400px;"></div>
                        
                        <script type="text/javascript">
                            $(document).ready(function(){
                                var this_data3 = new Array();
                                var this_ticks3 = new Array();

                                this_data3.push('<?php echo $res['received']; ?>');
                                this_data3.push('<?php echo $res['shipped']; ?>');

                                this_ticks3.push('Recibidas');
                                this_ticks3.push('Enviadas');

                                $.jqplot.config.enablePlugins = true;

                                plot3 = $.jqplot('jqplot_global_id_cop_bars', [this_data3], {
                                    title: 'Ordenes',
                                    animate: !$.jqplot.use_excanvas,

                                    seriesDefaults:{
                                        renderer:$.jqplot.BarRenderer,
                                        pointLabels: { show: true },
                                        rendererOptions: {
                                            varyBarColor : true,
                                            barDirection: 'vertical',
                                            barMargin: 4,
                                            barPadding: 4
                                        }
                                    },
                                    axes: {
                                        xaxis: {
                                            renderer: $.jqplot.CategoryAxisRenderer,
                                            tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                                            tickOptions:{ 
                        //                        angle: -30,
                                                fontSize: '10pt',
                                                markSize:15
                                            },
                                            ticks: this_ticks3

                                        },
                                        yaxis: {min: 0, max: 5}
                                    },
                                    highlighter : { show: false },
                                    cursor: {
                                        show: false
                                    }
                                });
                            });
                        
                        </script>
                        
                    </div>
                    <div id="tabs-ventas">
                        <table id="sales_table" class="altertable">
                            <thead>
                                <th>vendedor</th>
                                <th>apellido</th>
                                <th>monto</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $res2[0]['eno']; ?></td>
                                    <td><?php echo utf8_encode($res2[0]['ename']); ?></td>
                                    <td><?php echo $res2[0]['sales']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $res2[1]['eno']; ?></td>
                                    <td><?php echo utf8_encode($res2[1]['ename']); ?></td>
                                    <td><?php echo $res2[1]['sales']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <br/>
                        <div id="jqplot_global_sales_bars" style="height:300px; width:400px;"></div>
                        
                        <script type="text/javascript">
                            $(document).ready(function(){
                                var this_data4 = new Array();
                                var this_ticks4 = new Array();

                                this_data4.push('<?php echo $res2[0]['sales']; ?>');
                                this_data4.push('<?php echo $res2[1]['sales']; ?>');

                                this_ticks4.push('<?php echo utf8_encode($res2[0]['ename']); ?>');
                                this_ticks4.push('<?php echo utf8_encode($res2[1]['ename']); ?>');

                                $.jqplot.config.enablePlugins = true;

                                plot4 = $.jqplot('jqplot_global_sales_bars', [this_data4], {
                                    title: 'Ventas',
                                    animate: !$.jqplot.use_excanvas,

                                    seriesDefaults:{
                                        renderer:$.jqplot.BarRenderer,
                                        pointLabels: { show: true },
                                        rendererOptions: {
                                            varyBarColor : true,
                                            barDirection: 'vertical',
                                            barMargin: 4,
                                            barPadding: 4
                                        }
                                    },
                                    axes: {
                                        xaxis: {
                                            renderer: $.jqplot.CategoryAxisRenderer,
                                            tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                                            tickOptions:{ 
                        //                        angle: -30,
                                                fontSize: '10pt',
                                                markSize:15
                                            },
                                            ticks: this_ticks4

                                        },
                                        yaxis: {min: 0, max: 300}
                                    },
                                    highlighter : { show: false },
                                    cursor: {
                                        show: false
                                    }
                                });
                                
                                $('#tabs').bind('tabsshow', function(event, ui) {
                                    if (ui.index === 0 && plot3._drawCount === 0) {
                                      plot3.replot();
                                    }
                                    else if (ui.index === 1 && plot4._drawCount === 0) {
                                      plot4.replot();
                                    }
                                });
                            });
                        
                        </script>
                    </div>
                    <div id="tabs-imagenes">
                        <div id="accordion_img">
                            <h3>Script</h3>
                            <div>
                                <a href ="/files/respuestas.txt" target="_self">Descargar</a>
                            </div>
                            <h3>Pregunta 1</h3>
                            <div>
                                <pre>
declare
NON_NUM exception;
pragma exception_init(NON_NUM, -06502);
v_field_Delimiter varchar2(10) := 'a';
begin
        begin
                v_field_Delimiter := to_number(v_field_Delimiter);
                exception
                when NON_NUM then
                -- dbms_output.put_line('Not a number');
                RAISE;
        end;
-- continue processing
        dbms_output.put_line('Is a number');
        exception
        WHEN NON_NUM THEN dbms_output.put_line('External Not a number');
end;
                                </pre>
                                <img src="/images/Selection_001.png">
                            </div>
                            <h3>Pregunta 2</h3>
                            <div>
                                <pre>
CREATE OR REPLACE PROCEDURE agrega_empleo (
	v_job_id jobs.job_id%TYPE,
	v_job_title jobs.job_title%TYPE
)
IS
	v_job_exists INT;
	e_job_exists EXCEPTION;
BEGIN
	
	SELECT COUNT(DISTINCT job_id)
	INTO v_job_exists
	FROM jobs
	WHERE job_id = v_job_id;
	
	IF v_job_exists <> 0 THEN
		RAISE e_job_exists;
	ELSE
		INSERT INTO jobs
			(job_id, job_title) 
		VALUES (v_job_id, v_job_title);
	END IF;
	
	EXCEPTION
		WHEN e_job_exists THEN
		DBMS_OUTPUT.PUT_LINE ('Empleo ya existe.');
END;

BEGIN
agrega_empleo('IT_DBA','Database Administrator');
END;
BEGIN
agrega_empleo('IT_DBA','Database Administrator');
END;
                                </pre>
                                <img src="/images/Selection_002.png"><br />
                                <img src="/images/Selection_003.png"><br />
                                <img src="/images/Selection_004.png"><br />
                            </div>
                            <h3>Pregunta 3</h3>
                            <div>
                                <pre>
CREATE OR REPLACE FUNCTION obt_salanual_calc(
	v_salario NUMBER,
	v_comision NUMBER
)
RETURN NUMBER AS v_anual NUMBER(15,2);
v_s NUMBER(10,2); v_c NUMBER(10,2);
BEGIN

IF v_salario IS NULL THEN v_s := 0;
ELSE v_s := v_salario;
END IF;
IF v_comision IS NULL THEN v_c := 0;
ELSE v_c := v_comision;
END IF;

v_anual := v_s*12 + v_s*v_c*12;

RETURN v_anual;

END;

SELECT employee_id, obt_salanual_calc(salary,commission_pct) AS sa
FROM employees
WHERE department_id = 30;
                                </pre>
                                <img src="/images/Selection_005.png"><br />
                                <img src="/images/Selection_006.png">
                            </div>
                            <h3>Pregunta 4</h3>
                            <div>
                                <pre>
CREATE TABLE nuevo_empleado AS (
	SELECT employee_id, last_name, salary, department_id, email, job_id, hire_date
	FROM employees);
	
CREATE TABLE nuevo_departamento AS (
	SELECT 	department_id, department_name, location_id
	FROM departments);

CREATE OR REPLACE VIEW detalles_empleado AS
SELECT e.employee_id, e.last_name, e.salary, e.department_id, e.email, e.job_id, 
	d.department_name, d.location_id
FROM nuevo_empleado e
INNER JOIN nuevo_departamento d ON d.department_id = e.department_id;

	
CREATE OR REPLACE TRIGGER nuevo_empleado_departamento
INSTEAD OF INSERT ON detalles_empleado
REFERENCING NEW AS n
FOR EACH ROW
DECLARE
rowcnt number;
BEGIN
SELECT COUNT(*) INTO rowcnt FROM nuevo_departamento WHERE department_id = :n.department_id; 
IF rowcnt = 0  THEN
INSERT INTO nuevo_departamento (department_id, department_name, location_id) 
VALUES (:n.department_id, :n.department_name, :n.location_id);
ELSE
UPDATE nuevo_departamento SET department_name = :n.department_name, location_id = :n.location_id
WHERE department_id = :n.department_id;
END IF; 
SELECT COUNT(*) INTO rowcnt FROM nuevo_empleado WHERE employee_id = :n.employee_id;
IF rowcnt = 0  THEN
INSERT INTO nuevo_empleado (employee_id, last_name, salary, department_id, email, job_id) 
VALUES (:n.employee_id, :n.last_name, :n.salary, :n.department_id, :n.email, :n.job_id);
ELSE
UPDATE nuevo_empleado SET last_name = :n.last_name, salary = :n.salary, department_id = :n.department_id, email = :n.email, job_id = :n.job_id
WHERE employee_id = :n.employee_id;
END IF;
END;

CREATE OR REPLACE TRIGGER borrar_empleado_departamento
INSTEAD OF DELETE ON detalles_empleado
FOR EACH ROW

BEGIN

	DELETE FROM nuevo_empleado WHERE employee_id = :OLD.employee_id;
	DELETE FROM nuevo_departamento WHERE department_id = :OLD.department_id;
	
END;   

DELETE FROM detalles_empleado WHERE employee_id = 206;
                                </pre>
                                <img src="/images/Selection_007.png"><br />
                                <img src="/images/Selection_008.png"><br />
                                <img src="/images/Selection_009.png"><br />
                                <img src="/images/Selection_010.png"><br />
                                <img src="/images/Selection_011.png"><br />
                                <img src="/images/Selection_012.png"><br />
                                <img src="/images/Selection_013.png"><br />
                                <img src="/images/Selection_014.png">
                            </div>
                            <h3>Pregunta 5</h3>
                            <div>
                                <pre>
CREATE OR REPLACE PROCEDURE top_sales (
v_ini INT,
v_fin INT
)
IS
cnumber int;
BEGIN
dbms_output.put_line(CHR(9) || 'EmpNo' || CHR(9) || 'Name' || CHR(9) || 'Sales');
FOR v_res IN (
SELECT a.eno, a.ename, a.sales
FROM (
SELECT e.eno, e.ename, SUM(p.price*od.qty) AS sales, rank() over (order by SUM(p.price*od.qty) DESC) AS rnk FROM orders o
INNER JOIN employees e ON e.eno = o.eno
INNER JOIN odetails od ON od.ono = o.ono
INNER JOIN parts p ON p.pno = od.pno
GROUP BY e.eno, e.ename
) a
WHERE rnk BETWEEN v_ini AND v_fin
ORDER BY a.sales DESC
)
LOOP
dbms_output.put_line(CHR(9) || v_res.eno || CHR(9) || v_res.ename || CHR(9) || v_res.sales);
END LOOP;
END;

BEGIN
top_sales(1,3);
END;
                                </pre>
                                <img src="/images/Selection_015.png"><br />
                                <img src="/images/Selection_016.png">
                            </div>
                            <h3>Pregunta 7</h3>
                            <div>
                                <p>El problema ocurre al seleccionar datos de la misma tabla sobre la que existe el trigger, no es posible realizar la  operacion pues el sistema no puede garantizar la consistencia de los datos
                                    <br />Esto podria provocar inserciones con datos diferentes a lo planeado, o inclusive, en algunos casos causar un loop infinito
                                    <br />La solucion se obtiene usando la tabla JOBS para obtener los salarios mínimo y máximo de un dado job_id
                                </p>
                            </div>
                            <h3>Pregunta 8</h3>
                            <div>
                                <pre>
-- El trigger del PDF nunca compiló, propongo esta alternativa:

CREATE OR REPLACE TRIGGER verify_salary
BEFORE INSERT OR UPDATE OF salary, job_id ON EMPLOYEES
FOR EACH ROW
DECLARE
minsal NUMBER(8,2);
maxsal NUMBER(8,2);
e_i_sal EXCEPTION;
BEGIN
SELECT MIN(SALARY),MAX(SALARY)
INTO minsal,maxsal
FROM EMPLOYEES
WHERE JOB_ID=:NEW.JOB_ID;
IF :NEW.salary < minsal OR :new.salary > maxsal THEN
RAISE e_i_sal;
END IF;
EXCEPTION
WHEN e_i_sal THEN
DBMS_OUTPUT.PUT_LINE ('Salario fuera de rango.');
END;
                                </pre>                                
                                <pre>
-- Con los cambios respectivos para corregir el problema:

CREATE OR REPLACE TRIGGER verify_salary
BEFORE INSERT OR UPDATE OF salary, job_id ON EMPLOYEES
FOR EACH ROW
DECLARE
minsal NUMBER(8,2);
maxsal NUMBER(8,2);
e_i_sal EXCEPTION;
BEGIN
SELECT min_salary,max_salary
INTO minsal,maxsal
FROM JOBS
WHERE JOB_ID=:NEW.JOB_ID;
IF :NEW.salary < minsal OR :new.salary > maxsal THEN
RAISE e_i_sal;
END IF;
EXCEPTION
WHEN e_i_sal THEN
DBMS_OUTPUT.PUT_LINE ('Salario fuera de rango.');
END;
                                </pre>
                                <img src="/images/Selection_017.png"><br />
                                <img src="/images/Selection_018.png"><br />
                                <img src="/images/Selection_019.png">
                            </div>
                            <h3>Pregunta 9</h3>
                            <div>
                                <pre>
CREATE OR REPLACE PACKAGE pac_cursos AS
p_spots NUMBER;
PROCEDURE get_course_spots(v_dname VARCHAR2);
FUNCTION spots_filled (v_did NUMBER, v_cid VARCHAR2) 
RETURN INT;
END pac_cursos;

CREATE OR REPLACE PACKAGE BODY pac_cursos AS
PROCEDURE get_course_spots(v_dname VARCHAR2) IS
did NUMBER;

BEGIN

SELECT DEPT_ID
INTO did
FROM dept_master
WHERE dept_name LIKE v_dname;

dbms_output.put_line(CHR(9) || 'Course' || CHR(9) || 'Students Allowed');
FOR v_res IN (
SELECT d.dept_id, c.course_id, c.course_name, s.MAX_STUD_ALLOW
FROM COURSE_MASTER c
INNER JOIN DEPT_MASTER d ON d.dept_id = c.dept_id
INNER JOIN STRENGTH_MASTER s ON s.course_id = c.course_id AND s.dept_id = d.dept_id
WHERE c.dept_id = did
)
LOOP
dbms_output.put_line(CHR(9) || v_res.course_name || CHR(9) || v_res.MAX_STUD_ALLOW);
END LOOP;
dbms_output.put_line(CHR(10));
END get_course_spots;

FUNCTION spots_filled (v_did NUMBER, v_cid VARCHAR2) 
RETURN INT IS
spots INT;
BEGIN
SELECT COUNT(STUD_NO)
INTO spots
FROM STUDENT_DETAIL
WHERE DEPT_ID = v_did AND COURSE_ID = v_cid;

RETURN spots;
END spots_filled;

BEGIN
dbms_output.put_line('pac_cursos:');
END;

DECLARE
fs NUMBER;
BEGIN
pac_cursos.get_course_spots('COMPUTER SCIENCE');
fs := pac_cursos.spots_filled(101, 'C0001');
dbms_output.put_line(CHR(9) ||'Spots Filled: '||fs);
END;
                                </pre>
                                <img src="/images/Selection_020.png"><br />
                                <img src="/images/Selection_021.png"><br />
                                <img src="/images/Selection_022.png">
                            </div>
                            <h3>Pregunta 10</h3>
                            <div>
                                <p>Mismo caso del ejercicio 8 (en el cual ya implementé y solucioné), cualquier tabla en la que se esté ejecutando un UPDATE, INSERT o DELETE está "mutando", es decir,
                                    <br />sus datos están cambiando y oracle no puede garantizar la consistencia de los datos que se soliciten y, por lo tanto, bloquea
                                    <br />las operaciones que intenten hacer referencia a ella.
                                    <br />No se debe confundir con un MUTEX, que es la estructura usada para realizar este bloqueo. (al detectar un UPDATE, INSERT o DELETE
                                    <br />el sistema "activa" el mutex correspondiente a los recursos que se van a modificar, impidiendo que otros procesos tengan acceso a ese recurso)
                                </p>
                            </div>                            
                    </div>
                    <script type="text/javascript">
                            $(document).ready(function(){
                                $('#accordion_img').accordion({
                                    collapsible: true,
                                    heightStyle: "content"
                                });
                            }
                            );
                     </script>    
                </div>
            </div>
            <!-- footer -->
            <div id="footer">
                <ul class="main_header_right">
                    <li class="main_header_opt">
                        <span style="color:#ff6666;">Usuario:&nbsp;</span>
                        <?php
                            echo utf8_encode("{$_SESSION['idsys_user']}").'&nbsp;/&nbsp;'; 
                        ?>
                    </li>

                    <li class="main_header_opt_h" onclick="$(location).attr('href','logout.php');">
                        cerrar sesi&oacute;n
                        <span class="ui-icon grey ui-icon-power" style="display: inline-block; vertical-align: middle; margin-top: -2px;">&nbsp;</span>
                    </li>
                </ul>
            </div>
        </div>
        <div id="windows"></div>
    </body>
</html>

<?php } catch(SysException $e) { ?>
<div><?php echo $e->getMessage(); ?></div>
<?php } ?>
