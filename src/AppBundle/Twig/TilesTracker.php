<?php
namespace AppBundle\Twig;

class TilesTracker extends \Twig_Extension
{
    /**
     * Array containing tile IDs to choose from. By repeating an ID its weight
     * in the random selection is increased.
     */
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

    /**
     * Get the (weighted) array of tile IDs.
     * @returns Array of tile IDs
     */
    public function getTiles()
    {
        return $this->tiles;
    }

    /**
     * Marks a tile as renderd in the array of tiles. Essentially removes all
     * occurences of the ID.
     * @param $index - Tile ID
     */
    public function renderedTile($index) {
        $this->tiles = array_values(array_filter($this->tiles, function($tile) use($index) {
            return $tile != $index;
        }));
    }

    /**
     * Adds the the given tile ID $weight times to the array of IDs to choose
     * from.
     * @param $index - Tile ID
     * @param $weight - Weight of the tile
     */
    public function addTile($index, $weight) {
        for($i = 0; $i < $weight; ++$i)
        {
            $this->tiles[] = $index;
        }
    }

    /**
     * Set the array of tile IDs to choose from.
     * @param $array - Array of tile IDs
     */
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
