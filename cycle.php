<?php 

$config = require __DIR__.'/config.php';
require_once($config['smp_path']);

if(empty($argv[1])){
	die("Usage: command <SOURCE_FOLDER> [DESTINATION_FOLDER]\n");
}

$source = $argv[1];

if(!empty($argv[2])){
	$destination = $argv[2];
}


$files = glob($source."/*.wav");
$stdin = fopen("php://stdin","r");

$copied = "";

if(!file_exists(__DIR__.'/copied')){
	touch(__DIR__.'/copied');
}

$copied = file_get_contents(__DIR__.'/copied');

while(true){

	if(empty($files)){
		echo "Done.\n";
		break;
	}

	shuffle($files);

	echo PHP_EOL;
	echo " ============= OPTIONS ====================\n";
	echo "|\n";
	echo "|  rm + ENTER : remove sound\n";
	echo "|  mv + ENTER : move to destination folder \n";
	echo "|  r + ENTER : replay previous sound \n";
	echo "|  cp destination + ENTER : copy this sound to destination\n";
	echo "|  just ENTER : play next sound\n";
	echo "|\n";
	echo " ==========================================\n";
	echo PHP_EOL;

	foreach($files as $i => $file){

		$basename = basename($file);

		echo $basename;

		echo ' -- ';
		$smp = new FlSouto\Sampler($file);
		$bpm = round(16 / $smp->len() * 120);
		echo $bpm.'bpm';
	
		if(strstr($copied,$basename)){
			echo ' **COPIED**';
		}
		
		echo PHP_EOL;

		play:
		exec("play $file > /dev/null 2>&1 &");
		
		$input = trim(fgets($stdin));
		$line = current(explode("\n",`ps aux | grep play | grep -v Manager`));
		if(strstr($line,'.wav')){
			preg_match("/(\d{3,})/",$line,$match);
			shell_exec("kill ".$match[0]);
			sleep(1);
		}

		if($input=='rm'){
			shell_exec("rm $file");
			unset($files[$i]);
		}

		else if(substr($input,0,3)=='cp '){
			shell_exec("cp $file ".substr($input,3));
			file_put_contents("copied",$basename."\n",FILE_APPEND);
			$copied .= "\n".$basename;
		}

		else if($input=='mv'){
			if(empty($destination)){
				die("Destination folder was not given.\n");
			} else {
				shell_exec("mv $file $destination");
				unset($files[$i]);
			}
		}

		else if($input=='r'){
			goto play;
		}

	}

}


