<?php
namespace Home\Controller;
use Think\Controller;

class AutoController extends CommonController {
	
	
	private function titleassgin() {
		$this -> assign(titleA,"自动化首页");
		$this -> assign(titleAlink,__CONTROLLER__."/index");
		
				
		$data = array(
		array('name' => "个人设置", link => __MODULE__."/Profile/myauto"),
		);
		$this -> assign(othertitle,$data);
	}
	
    // 框架首页
    public function index() {
		$this -> assign(title,"XenDesktop自动化");
		$this -> assign(description,"XD VDA自动生成配置");
		$this -> titleassgin();
		
		// $cmd = 'C:/1.bat';
		// system("C:\\1.bat",$out);
		// dump($out);
		
		/* 通过文件目录抓取XenServer信息 -- 过期
		foreach ( new \DirectoryIterator('..\Auto\XenServer') as $xsbuild ) {
			if(htmlentities($xsbuild) === '.' || htmlentities($xsbuild) === '..'){
			continue;
			}
			$xsbuildvalue[] = htmlentities($xsbuild);
			// echo '<option value="'.htmlentities($build).'">'.htmlentities($build).'</option>';
		}
		// $xsbuild = new \DirectoryIterator('..\Auto\XenServer');
		
		// dump($xsbuildvalue);
		*/
		
		system("..\Auto\build.bat");
		
		// $Server	= M('AutoServer');
		// $Domain	= M('AutoDc');
		// $XDDDC = M('AutoDdc');
		
		if ($xs = M('AutoServer')->where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'"')->order('`default` desc,`id`')->select())
		{
			$this -> assign(xs,$xs);
		}
		else
		{
			$this -> assign(xs,0);
		}
			
		if ($dc = M('AutoDc')->where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'"')->order('`default` desc,`id`')->select())
		{
			$this -> assign(dc,$dc);
		}
		else
		{
			$this -> assign(dc,0);
		}
		
		if ($ddc = M('AutoDdc')->where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'"')->order('`default` desc,`id`')->select())
		{
			$this -> assign(ddc,$ddc);
		}
		else
		{
			$this -> assign(ddc,0);
		}
		
		// $this -> assign(xs,$Server->where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'"')->select());
		// $this -> assign(dc,$Domain->where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'"')->select());
		// $this -> assign(ddc,$XDDDC->where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'"')->select());
		
		
		foreach ( new \DirectoryIterator('W:\\') as $minibuild ) {
			if(htmlentities($minibuild) === '.' || htmlentities($minibuild) === '..'){
			continue;
			}
			$minibuildvalue[] = htmlentities($minibuild);
			// echo '<option value="'.htmlentities($build).'">'.htmlentities($build).'</option>';
		}
		

		
		foreach ( new \DirectoryIterator('L:\\') as $fullbuild ) {
			if(htmlentities($fullbuild) === '.' || htmlentities($fullbuild) === '..'){
			continue;
			}
			$fullbuildvalue[] = htmlentities($fullbuild);
			// echo '<option value="'.htmlentities($build).'">'.htmlentities($build).'</option>';
		}
		
		$this -> assign(mini,$minibuildvalue);
		$this -> assign(full,$fullbuildvalue);
		
		$this -> display();
		

    }
	
	public function addddc() {
		$ddc = M('AutoDdc');
		if($ddc->where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'" and name="'.$_POST['name'].'"')->select())
		{
			$this -> error("Data already exists");
		}
		else if($ddc -> create()) {
			$ddc -> userid = $_SESSION[C('USER_AUTH_KEY')];
			$ddc -> add();
			$this -> success("Added successfully");
		}
		else {
			$this -> error("Added failure");
		}
		

		// redirect(__CONTROLLER__.'/index');
	}
	
	public function adddc() {
		$dc = M('AutoDc');
		if($dc->where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'" and name="'.$_POST['name'].'"')->select())
		{
			$this -> error("Data already exists");
		}
		else if($dc -> create()) {
			$dc -> userid = $_SESSION[C('USER_AUTH_KEY')];
			$dc -> add();
			$this -> success("Added successfully");
		}
		else {
			$this -> error("Added failure");
		}
		
		// redirect(__CONTROLLER__.'/index');
	}
	
	public function addserver() {
		$xs = M('AutoServer');
		if($xs->where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'" and ip="'.$_POST['ip'].'"')->select())
		{
			$this -> error("Data already exists");
		}
		else if($xs -> create()) {
			$xs -> userid = $_SESSION[C('USER_AUTH_KEY')];
			$xs -> add();
			$this -> success("Added successfully");
		}
		else {
			$this -> error("Added failure");
		}
	}
	
	public function action() {
		
		$xs = M('AutoServer');
		$dc = M('AutoDc');
		$ddc = M('AutoDdc');
		
		switch($_POST['action'])
		{

		case 'del':
			if($_POST['type'] == 'xs'){
				$xs -> where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'" and id="'.$_POST['id'].'"')->delete(); 
				
			}
			else if($_POST['type'] == 'dc'){
				$dc -> where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'" and id="'.$_POST['id'].'"')->delete(); 
				
			}
			else if($_POST['type'] == 'ddc'){
				$ddc -> where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'" and id="'.$_POST['id'].'"')->delete(); 
				
			}
			else {
				$data['status'] = 0;
				$data['info'] = "出错啦";
				$this->ajaxReturn($data);
				break;
			}
			
			$data['status'] = 1;
			$data['info'] = "Deleted Successfully";
			$this->ajaxReturn($data);
			break;
			
		case 'def':
			if($_POST['type'] == 'xs'){
				$xs -> default = 0;
				$xs -> where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'"')->save(); 
				
				$xs->default = 1;
				$xs -> where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'" and id="'.$_POST['id'].'"')->save(); 
				
			}
			else if($_POST['type'] == 'dc'){
				$dc -> default = 0;
				$dc -> where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'"')->save(); 
				
				$dc->default = 1;
				$dc -> where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'" and id="'.$_POST['id'].'"')->save(); 
				
			}
			else if($_POST['type'] == 'ddc'){
				$ddc -> default = 0;
				$ddc -> where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'"')->save(); 
				
				$ddc -> default = 1;
				$ddc -> where('userid ="'.$_SESSION[C('USER_AUTH_KEY')].'" and id="'.$_POST['id'].'"')->save(); 
				
			}
			else {
				$data['status'] = 0;
				$data['info'] = "出错啦";
				$this->ajaxReturn($data);
				break;
			}
			
			$data['status'] = 1;
			$data['info'] = "Applied Successfully";
			$this->ajaxReturn($data);
			break;
		}

	}
	
	public function run() {
		// dump($_POST['cdc']);
		// dump($_POST['cddc']);
		// dump($_POST['cvdanum']);
		// dump($_POST['cvdaos']);
		// dump($_POST['cvdabuild']);

		$isoName=md5(time());
		mkdir('..\Auto\Conf\\'.$isoName,0777); 

		$temp_domain = fopen('..\Auto\Conf\\'.$isoName.'/Domainlist.ini','w') or die("can't open ini file: $php_errormsg");
		if(-1 == fwrite($temp_domain,$_POST['cdc'])) { die("can't write ini file: $php_errormsg");}
		fclose($temp_domain) or die("can't close ini file: $php_errormsg");

		$temp_ddc = fopen('..\Auto\Conf\\'.$isoName.'/DDClist.ini','w') or die("can't open ini file: $php_errormsg");
		if(-1 == fwrite($temp_ddc,$_POST['cddc'])) { die("can't write ini file: $php_errormsg");}
		fclose($temp_ddc) or die("can't close ini file: $php_errormsg");


		if($_POST['cvda'] == 'mini')
		{
			// system("..\Auto\TOTVTS.bat ".$isoName." C:\wamp\www\Auto\Conf\\".$isoName." ".$_POST['cvdanum']." ".$_POST['cvdaos']." ".$_POST['cvdabuild'].">nul");

		}
		else if($_POST['cvda'] == 'full')
		{
			// system("..\Auto\TOTVTSiSO.bat");
			// system("..\Auto\TOTVTSiSO.bat ".$isoName." C:\wamp\www\Auto\Conf\\".$isoName." ".$_POST['cvdanum']." ".$_POST['cvdaos']." ".$_POST['cvdabuild'].">nul");

		}
		
		Header('Location: result');
	}
	
	public function result() {
		$this -> assign(title,"XenDesktop自动化");
		$this -> assign(description,"XD VDA自动生成配置");
		$this -> titleassgin();
		
		$this->display();
	}
	
}
