<?php
namespace AppBundle\Twig;

class TilesTracker extends \Twig_Extension
{
    private $tiles = array();

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('gettiles', array($this, 'getTiles')),
            new \Twig_SimpleFunction('rendertile', array($this, 'renderedTile')),
            new \Twig_SimpleFunction('addtile', array($this, 'addTile')),
            new \Twig_SimpleFunction('settiles', array($this, 'setTiles'))
        );
    }

    public function getTiles()
    {
        return $this->tiles;
    }

    public function renderedTile($index) {
        $this->tiles = array_values(array_filter($this->tiles, function($tile) use($index) {
            return $tile != $index;
        }));
    }

    public function addTile($index, $weight) {
        for($i = 0; $i < $weight; ++$i)
        {
            $this->tiles[] = $index;
        }
    }

    public function setTiles($array)
    {
        $this->tiles = $array;
    }

    public function getName()
    {
        return 'tiles_tracker';
    }
}
?>
