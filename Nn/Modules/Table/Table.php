<?php

namespace Nn\Modules\Table;
use Nn;
use Utils;

class Table extends Nn\Modules\Attachment\Attachment {

	protected $content;

	public static $SCHEMA = array(
			'title' => 'short_text',
			'description' => 'text',
			'filename' => 'short_text',
			'content' => 'long_text'
		);

	public function make($title, $description, $file){
		if($this->attach_file($file)){
			$this->title = $title;
			$this->description = $description;
			return $this;
		} else {
			return false;
		}
	}

	public function content() {
		
		if(true) {
		
			$tmp_dir = Utils::dir(ROOT.DS.'tmp'.DS.'tables-'.$this->id);

			$zip = new \ZipArchive;
			$zip->open($this->path());
			$zip->extractTo($tmp_dir);

			$strings = simplexml_load_file($tmp_dir.DS.'xl'.DS.'sharedStrings.xml');
			$sheet = simplexml_load_file($tmp_dir.DS.'xl'.DS.'worksheets'.DS.'sheet1.xml');

			$xlrows = $sheet->sheetData->row;

			$headers = [];
			$table = [];

			foreach($xlrows as $xlrow) {
				
				$array = [];

				foreach($xlrow->c as $cell) {
					$val = (string)$cell->v;

					if(isset($cell['t']) && $cell['t'] == 's') {
						$s = [];
						$si = $strings->si[(int)$val];
						$si->registerXPathNamespace('n','http://schemas.openxmlformats.org/spreadsheetml/2006/main');
						foreach($si->xpath('.//n:t') as $t) {
							$s[] = (string)$t;
						}
						$val = implode($s);
					}
					$array[] = $val;
				}

				if(count($headers) == 0) {
					$headers = $array;
					$table_headers = [];
					foreach($headers as $header) {
						$table_headers[$header] = 'HEADER';
					}
					$table[] = $table_headers;
				} else {
					$vals = array_pad($array, count($headers), '');
					$table[] = array_combine($headers, $vals);
				}

			}

			$this->content = serialize($table);
			$this->save();
			// unlink($tmp_dir);
		}

		return unserialize($this->content);
	}

}
