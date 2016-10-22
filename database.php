<?php 
//masukkan databse frameworknya
require_once 'medoo.php';

//koneksikan ke database
//contoh penggunaannya  ke SQLite

$database = new medoo([
	'databse_type' => 'SQLite',
	'database_file' => 'diary.db'
	]);

//insert atau menambahkan ke database, yaitu diary
function diary_tambah($iduser,$pesan){
	global $database;
	$last_id = $database -> insert ('catatan',[
		'id' => $iduser,	
		'waktu' => date('y-m-d  H:i:s'). ' WIB',
		'pesan' => $pesan,
		]);
	return  $last_id;
}
//menhapus diary dari database
function diaryhapus($iduser, $idpesan){
	global $database;
	$database -> delete('catatan',[
		'AND' => [
		'id' => $iduser,
		'no' => $idpesan,
		],
		] );
	return ' telah dilaksanakan...';
}
//fungsi untuk melihat diary
function diarylist ($iduser, $page = 0){
	global $database;
	$hasil = 'maaf tidak ada catatan di diaryku';
	$datas = $database -> select ('catatan',[
		'no',
		'id',
		'waktu',
		'pesan',
		], [
			'id' => $iduser,
		]);
	$jml = count($datas);
	if ($jml > 0 ){
		$hasil = "$jml Catatan Diarymu telah tersimpan denganr api di hatiku  ";
		$n = 0 ;
		foreach ($datas as $data ) {
			# code...
			$n++;
			$hasil .= "\$n. " .substr($data['pesan'],0,10).".. \n  '$data[waktu]' \n ";
			$hasil .= "\n /view\_$data[no]\n" ;
		}
	}
	return $hasil;
}

//fungsi untuk melihat isi pesan diary
function diaryview ($iduser, $idpesan){
	global $database;
	$hasil = "maaf ya diarymu yang itu tidak ditemukan dihatiku. \n Mungkin saja bukan buatmu.." ;
	$datas = $database -> select('catatan',[
		'no',
		'id',
		'waktu',
		'pesan',
		], [
		'AND'  => [
			'id' => $iduser,
			'no' => $idpesan,
			],
		]);
	$jml = count($datas);
	if($jml > 0){
		$data = $datas[0];
		$hasil = "diary nomor $data[no]  yang tersimpan di hatiku berisi : \n ------------------ \n";
		$hasil .= "\n$data[pesan] \n ----------\n $data[waktu] " ;
		$hasil .= "\n \n hapus /hapus\_$data[no]";
	}
	return $hasil;
}

//fungsi untuk mencari diary
function searchingdiary ($iduser,$pesan){
	global $database;
	$hasil = "maaf ya yang selama ini aku cari belum ketemu";
	$datas = $database('catatan',[
		'no',
		'id',
		'waktu',
		'pesan',
		], [ 
			'pesan[~]' => $pesan,
		]);
	if($jml > 0){
		$hasil = " $jml catatan diary selalu kusimpan dihati \n";
		$n = 0;
		foreach ($datas as $data ) {
			# code...
			$n++;
			$hasil .= "\n$n. ".substr($data['pesan'],0,10). "... \n '$data[waktu]'\n";
			$hasil .= "\n /view\_$data[no]\n";
		}
	}
	return $hasil;
}

 ?>