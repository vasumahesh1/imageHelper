<?php
/*
Image Helper Class
Vasu Mahesh
Technited
*/
class imageHelper
{
	public $originalLocation;
	public $source;
	public $width;
	public $height;
	public $aspectRatio;
	public $extension;
	public $resize;
	public $thumb;
	public $unlink=false;

	function __construct($loc,$unlink)
	{
		$this->originalLocation = $loc;
		$this->extension = end(explode(".",$loc));
		list($this->width, $this->height) = getimagesize($loc);
		$this->aspectRatio = $this->width/$this->height;
		$this->unlink = $unlink;
	}
	/* MAIN FUNCTIONS */


	/* Generate Thumbnail from Image - Mode = FILL */
	function generateThumb($destinationLocation,$finalWidth=100,$finalHeight=100)
	{		
		$this->createImage();
		$this->thumb = imagecreatetruecolor($finalWidth, $finalHeight);
		$reqwidth = $this->aspectRatio*$finalHeight;
		$reqheight = (1/$this->aspectRatio)*$finalWidth;
		if($finalWidth>$reqwidth)
		{
			$reqheight = (1/$this->aspectRatio)*$finalWidth;
			$this->resize = imagecreatetruecolor($finalWidth, $reqheight);
			$sampleHeight = ($reqheight-$finalHeight)/2;
			imagecopyresized($this->resize, $this->source, 0, 0, 0, 0, $finalWidth, $reqheight , $this->width, $this->height);
			imagecopy($this->thumb, $this->resize, 0, 0, 0, $sampleHeight, $finalWidth, $finalHeight);
		}
		else
		{
			$reqwidth = ($this->aspectRatio)*$finalHeight;
			$this->resize = imagecreatetruecolor($reqwidth, $finalHeight);
			$sampleWidth = ($reqwidth-$finalWidth)/2;
			imagecopyresized($this->resize, $this->source, 0, 0, 0, 0, $reqwidth, $finalHeight , $this->width, $this->height);
			imagecopy($this->thumb, $this->resize, 0, 0, $sampleWidth, 0, $reqwidth, $finalHeight);
		}
		$this->storeImage($this->thumb,$destinationLocation);
	}

	/* Generate Thumbnail from Image - Mode = Contain */
	function generateThumbNoCrop($destinationLocation,$finalWidth=100,$finalHeight=100,$r=255,$g=255,$b=255)
	{
		$this->createImage();
		$this->thumb = imagecreatetruecolor($finalWidth, $finalHeight);
		if($this->width > $this->height)
		{
			$reqheight = (1/$this->aspectRatio)*$finalWidth;
			$this->resize = imagecreatetruecolor($finalWidth, $finalHeight);
			$this->resize = $this->fillImageBg($this->resize ,$r,$g,$b);
			$sampleHeight = ($finalHeight-$reqheight)/2;
			imagecopyresized($this->resize, $this->source, 0, $sampleHeight, 0, 0, $finalWidth, $reqheight , $this->width, $this->height);
		}
		else
		{
			$reqwidth = $this->aspectRatio*$finalHeight;
			$this->resize = imagecreatetruecolor($finalWidth, $finalHeight);
			$this->resize = $this->fillImageBg($this->resize ,$r,$g,$b);
			$sampleWidth = ($finalWidth-$reqwidth)/2;
			imagecopyresized($this->resize, $this->source, $sampleWidth, 0, 0, 0, $reqwidth, $finalHeight , $this->width, $this->height);
		}
		if($this->unlink==true)
		{
			unlink($this->originalLocation);
		}
		$this->storeImage($this->resize,$destinationLocation);
	}

	/* Resize Image */
	function changeSize($destinationLocation,$ratio=0.5)
	{
		$this->createImage();
		$reqwidth = $ratio*$this->width;
		$reqheight = $ratio*$this->height;
		$this->resize = imagecreatetruecolor($reqwidth, $reqheight);
		imagecopyresized($this->resize, $this->source, 0, 0, 0, 0, $reqwidth, $reqheight , $this->width, $this->height);
		$this->storeImage($this->resize,$destinationLocation);
	}

	function changeSizeToHeight($destinationLocation,$finalHeight=700)
	{
		$this->createImage();
		$reqwidth = $this->aspectRatio*$finalHeight;
		$this->resize = imagecreatetruecolor($reqwidth, $finalHeight);
		imagecopyresized($this->resize, $this->source, 0, 0, 0, 0, $reqwidth, $finalHeight , $this->width, $this->height);
		$this->storeImage($this->resize,$destinationLocation);
	}
	function changeSizeToWidth($destinationLocation,$finalWidth=700)
	{
		$this->createImage();
		$reqheight = (1/$this->aspectRatio)*$finalWidth;
		$this->resize = imagecreatetruecolor($finalWidth, $reqheight);
		imagecopyresized($this->resize, $this->source, 0, 0, 0, 0, $finalWidth, $reqheight , $this->width, $this->height);
		$this->storeImage($this->resize,$destinationLocation);
	}


	/*  CLASS HELPERS  */
	function createImage()
	{
		if($this->extension == 'jpg')
		$this->source = imagecreatefromjpeg($this->originalLocation);
		else if($this->extension == 'png')
		$this->source = imagecreatefrompng($this->originalLocation);
	}
	function fillImageBg($image,$red,$green,$blue)
	{
		$bg = imagecolorallocate($image, $red, $green, $blue);
		imagefill($image, 0, 0, $bg);
		return $image;
	}
	function storeImage($image,$destinationLocation)
	{
		if($this->unlink==true)
		{
			unlink($this->originalLocation);
		}
		if($this->extension == 'jpg')
		imagejpeg($image,$destinationLocation);
		else
		imagepng($image,$destinationLocation);
	}
}
// function generateThumb($destinationLocation,$originalLocation,$this->unlink=false,$finalWidth=100,$finalHeight=100)
// {
// 	$filename = $originalLocation;
// 	$this->extension = end(explode(".",$filename));
// 	list($this->width, $this->height) = getimagesize($filename);
// 	$this->aspectRatio = $this->width/$this->height;
// 	if($this->extension == 'jpg')
// 	$this->source = imagecreatefromjpeg($filename);
// 	else
// 	$this->source = imagecreatefrompng($filename);
// 	$this->thumb = imagecreatetruecolor($finalWidth, $finalHeight);
// 	$reqwidth = $this->aspectRatio*$finalHeight;
// 	$reqheight = (1/$this->aspectRatio)*$finalWidth;
// 	if($finalWidth>$reqwidth)
// 	{
// 		$reqheight = (1/$this->aspectRatio)*$finalWidth;
// 		$this->resize = imagecreatetruecolor($finalWidth, $reqheight);
// 		$sampleHeight = ($reqheight-$finalHeight)/2;
// 		imagecopyresized($this->resize, $this->source, 0, 0, 0, 0, $finalWidth, $reqheight , $this->width, $this->height);
// 		imagecopy($this->thumb, $this->resize, 0, 0, 0, $sampleHeight, $finalWidth, $finalHeight);
// 	}
// 	else
// 	{
// 		$reqwidth = ($this->aspectRatio)*$finalHeight;
// 		$this->resize = imagecreatetruecolor($reqwidth, $finalHeight);
// 		$sampleWidth = ($reqwidth-$finalWidth)/2;
// 		imagecopyresized($this->resize, $this->source, 0, 0, 0, 0, $reqwidth, $finalHeight , $this->width, $this->height);
// 		imagecopy($this->thumb, $this->resize, 0, 0, $sampleWidth, 0, $reqwidth, $finalHeight);
// 	}
// 	if($this->unlink==true)
// 	{
// 		unlink($originalLocation);
// 	}
// 	if($this->extension == 'jpg')
// 	imagejpeg($this->thumb,$destinationLocation);
// 	else
// 	imagepng($this->thumb,$destinationLocation);
// }
// function generateThumbNoCrop($destinationLocation,$originalLocation,$this->unlink=false,$finalWidth=100,$finalHeight=100,$r=255,$g=255,$b=255)
// {
// 	$filename = $originalLocation;
// 	$this->extension = end(explode(".",$filename));
// 	list($this->width, $this->height) = getimagesize($filename);
// 	$this->aspectRatio = $this->width/$this->height;
// 	if($this->extension == 'jpg')
// 	$this->source = imagecreatefromjpeg($filename);
// 	else if($this->extension == 'png')
// 	$this->source = imagecreatefrompng($filename);
// 	$this->thumb = imagecreatetruecolor($finalWidth, $finalHeight);
// 	if($this->width > $this->height)
// 	{
// 		$reqheight = (1/$this->aspectRatio)*$finalWidth;
// 		$this->resize = imagecreatetruecolor($finalWidth, $finalHeight);
// 		$this->resize = fillImageBg($this->resize ,$r,$g,$b);
// 		$sampleHeight = ($finalHeight-$reqheight)/2;
// 		imagecopyresized($this->resize, $this->source, 0, $sampleHeight, 0, 0, $finalWidth, $reqheight , $this->width, $this->height);
// 	}
// 	else
// 	{
// 		$reqwidth = $this->aspectRatio*$finalHeight;
// 		$this->resize = imagecreatetruecolor($finalWidth, $finalHeight);
// 		$this->resize = fillImageBg($this->resize ,$r,$g,$b);
// 		$sampleWidth = ($finalWidth-$reqwidth)/2;
// 		imagecopyresized($this->resize, $this->source, $sampleWidth, 0, 0, 0, $reqwidth, $finalHeight , $this->width, $this->height);
// 	}
// 	if($this->unlink==true)
// 	{
// 		unlink($originalLocation);
// 	}
// 	if($this->extension == 'jpg')
// 	imagejpeg($this->resize,$destinationLocation);
// 	else if($this->extension == 'png')
// 	imagepng($this->resize,$destinationLocation);
// }
// function reduceSize($destinationLocation,$originalLocation,$this->unlink=false,$ratio=0.5)
// {
// 	$filename = $originalLocation;
// 	$this->extension = end(explode(".",$filename));
// 	list($this->width, $this->height) = getimagesize($filename);
// 	if($this->extension == 'jpg')
// 	$this->source = imagecreatefromjpeg($filename);
// 	else
// 	$this->source = imagecreatefrompng($filename);
// 	$reqwidth = $ratio*$this->width;
// 	$reqheight = $ratio*$this->height;
// 	$this->resize = imagecreatetruecolor($reqwidth, $reqheight);
// 	imagecopyresized($this->resize, $this->source, 0, 0, 0, 0, $reqwidth, $reqheight , $this->width, $this->height);
// 	if($this->unlink==true)
// 	{
// 		unlink($originalLocation);
// 	}
// 	if($this->extension == 'jpg')
// 	imagejpeg($this->resize,$destinationLocation);
// 	else
// 	imagepng($this->resize,$destinationLocation);
// }
// function reduceSizeToHeight($destinationLocation,$originalLocation,$this->unlink=false,$finalHeight=700)
// {
// 	$filename = $originalLocation;
// 	$this->extension = end(explode(".",$filename));
// 	list($this->width, $this->height) = getimagesize($filename);
// 	$this->aspectRatio = $this->width/$this->height;
// 	if($this->extension == 'jpg')
// 	$this->source = imagecreatefromjpeg($filename);
// 	else
// 	$this->source = imagecreatefrompng($filename);
// 	$reqwidth = $this->aspectRatio*$finalHeight;
// 	$this->resize = imagecreatetruecolor($reqwidth, $finalHeight);
// 	imagecopyresized($this->resize, $this->source, 0, 0, 0, 0, $reqwidth, $finalHeight , $this->width, $this->height);
// 	if($this->unlink==true)
// 	{
// 		unlink($originalLocation);
// 	}
// 	if($this->extension == 'jpg')
// 	imagejpeg($this->resize,$destinationLocation);
// 	else
// 	imagepng($this->resize,$destinationLocation);
// }
function debugImage($image)
{
	echo "<br>Width :".imagesx($image)."<br>Height :".imagesy($image);

}
?>
