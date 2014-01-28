<?php
    $jira_xml   = 'PL467.xml';
    echo '<pre>';

    $dah = new SGJira($jira_xml);
    //die(print_r($dah->project));

    class SGJira {
        var $project        = null;
        var $project_key    = null;
        var $items          = array();

        function __construct($path_to_xml)
        {
            $this->project      = new SimpleXMLElement(file_get_contents($path_to_xml));
            $this->project_key  = 'PL467';

            define('KEY_INFO', $this->project_key.'-1');
            define('KEY_RD', $this->project_key.'-2');
            define('KEY_KEYTURNS', $this->project_key.'-22');
            define('KEY_DEV', $this->project_key.'-29');

            define('TEMPLATE_TASK', 'templates/task.inc.php');

            $this->prepare_data();
            $this->write_project();
        }

        function prepare_data()
        {
            foreach ($this->project->xpath('//item') AS $item) {
                $key = (string)$item->key;
                $key = explode('-', $key);

                if ($key[1] < 10)
                    $key[1] = '0'.$key[1];
                $key = implode($key, '-');

                $items[$key] = $item;
            }

            ksort($items);

            foreach ($items AS $key => $item) {
                $key = explode('-', $key);
                if ($key[1] < 10)
                    $key[1] = substr($key[1], 1);
                $key = implode($key, '-');

                $this->items[$key] = $item;
            }
die(print_r($this->items,true));
        }

        function write_project()
        {
            $output = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<Project xmlns=\"http://schemas.microsoft.com/project\">\n";

            $output .= $this->write_project_meta();
            $output .= $this->write_project_calendar();
            $output .= $this->write_project_tasks();

            $output .= "\n</Project>";

            // This goes to somewhere you can write a file to.
            $fp = fopen('test.xml', 'w')
                OR die('couldn\'t open file');
            fwrite($fp, $output);
            fclose($fp);

            die($output);
        }

        function write_project_meta()
        {
            $key = $this->project_key.'-1';

            $data['title'] = $this->project_key;
            $data['current_date'] = date('Y-m-d\TH:i:s');
            $data['subject'] = preg_replace('/'.$this->project_key.' /', '', $this->items[$key]->project);
            $data['created'] = date('Y-m-d\TH:i:s', strtotime($this->items[$key]->created));
            $data['updated'] = date('Y-m-d\TH:i:s', strtotime($this->items[$key]->updated));

            $template = require_once('templates/meta.inc.php');

            return $template;
        }

        function write_project_calendar()
        {
            $template = require_once('templates/calendar.inc.php');

            return $template;
        }

        function write_project_tasks()
        {            
            $i = 0;
            $output = "\n    <Tasks>\n";

            // Project Wrapper (To visualize percentage complete of project)
            $data['i'] = $i++;
            $data['title'] = preg_replace('/\[.*-1\] /', '', $this->items[KEY_INFO]->title);
            $data['outline'] = 1;
            $data['indent'] = 1;
            $output .= require(TEMPLATE_TASK);
            unset($this->items[KEY_INFO]);

            // Release Deliverables
            $output .= $this->_write_task(KEY_RD, $i, '1.1', 2);

            // Key Turns
            $output .= $this->_write_task(KEY_KEYTURNS, $i, '1.1', 2);

            $data['i'] = $i++;
            $data['title'] = 'Development';
            $data['outline'] = '1.1';
            $data['indent'] = 2;
            $output .= require(TEMPLATE_TASK);

            // Development - By virtue of being all that's left
            foreach ($this->items AS $key => $item) {
                if (isset($this->items[$key])) {
                    $output .= $this->_write_task($key, $i, '1.1.1', 3);
                }
            }

            $output .= "    </Tasks>";

            return $output;
        }

        function _write_task($key, &$i, $outline, $indent)
        {
            $output          = '';
            $data['i']       = $i++;
            $data['title']   = (string)$this->items[(string)$key]->title;
            $data['outline'] = $outline;
            $data['indent']  = $indent;
            $output = require(TEMPLATE_TASK);

            if (is_object($this->items[(string)$key]->subtasks->subtask)) {
                foreach ($this->items[(string)$key]->subtasks->subtask AS $mt => $subtask) {
                    $output .= $this->_write_task($subtask, $i, $outline.'.1', ($indent+1));
                }
            }

            unset($this->items[(string)$key]);

            return $output;
        }
    }
