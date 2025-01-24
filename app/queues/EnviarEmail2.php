<?php 
class EnviarEmail2 {

    public function fire($job, $data)
    {
        Mail::send('hello', array(), function ($m) 
		{
			$m->subject($data);
			$m->from(array('notificaciones@manizales.gov.co' => 'Notificaciones'));
			$m->to('carlos.ramirez@manizales.gov.co');
		});	

    	$job->delete();
    }

}