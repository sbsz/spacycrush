<?php

/*
 * Ce fichier fait partie du jeu Spacycrush
 */

namespace Spacycrush\Test\Model;

use Spacycrush\Test\TestCase;
use Spacycrush\Element\Grid\Grid;
use Spacycrush\Element\Grid\Box;

use Spacycrush\Generator\SimpleGridGenerator;

/**
 * Test une grille et ses cases
 *
 * @author Steven Bsz
 */
class GridTest extends TestCase
{

	/* Grid test :

		1    2    1    3
		1    3    2    1
		1    2    2    2
		3    3    2    3

	*/
	private function createGrid()
	{
		$genGrid = new SimpleGridGenerator(array('size' => 4));
		$grid = $genGrid->generates();

		$typeArray = [1,2,1,3,1,3,2,1,1,2,2,2,3,3,2,3];
		for ($i=0; $i < 16; $i++) {
			$grid->getContainer()->findBy('position', $i)->setType($typeArray[$i]);
		}

		return $grid;
	}


	public function testBoxesGridIsCorrect()
	{
		$grid = $this->createGrid();

		/* Box 0 */
		$this->assertEquals(-1,														$grid->getContainer()->findBy('position', 0)->getNeighbor('top'));
		$this->assertEquals($grid->getContainer()->findBy('position', 1)->getId(), 	$grid->getContainer()->findBy('position', 0)->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 4)->getId(), 	$grid->getContainer()->findBy('position', 0)->getNeighbor('bottom'));
		$this->assertEquals(-1,														$grid->getContainer()->findBy('position', 0)->getNeighbor('left'));

		/* Box 1 */
		$this->assertEquals(-1, 													$grid->getContainer()->findBy('position', 1)->getNeighbor('top'));
		$this->assertEquals($grid->getContainer()->findBy('position', 2)->getId(),	$grid->getContainer()->findBy('position', 1)->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 5)->getId(),	$grid->getContainer()->findBy('position', 1)->getNeighbor('bottom'));
		$this->assertEquals($grid->getContainer()->findBy('position', 0)->getId(),	$grid->getContainer()->findBy('position', 1)->getNeighbor('left'));

		/* Box 3 */
		$this->assertEquals(-1, 													$grid->getContainer()->findBy('position', 3)->getNeighbor('top'));
		$this->assertEquals(-1, 													$grid->getContainer()->findBy('position', 3)->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 7)->getId(),	$grid->getContainer()->findBy('position', 3)->getNeighbor('bottom'));
		$this->assertEquals($grid->getContainer()->findBy('position', 2)->getId(),	$grid->getContainer()->findBy('position', 3)->getNeighbor('left'));

		/* Box 4 */
		$this->assertEquals($grid->getContainer()->findBy('position', 0)->getId(),	$grid->getContainer()->findBy('position', 4)->getNeighbor('top'));
		$this->assertEquals($grid->getContainer()->findBy('position', 5)->getId(),	$grid->getContainer()->findBy('position', 4)->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 8)->getId(),	$grid->getContainer()->findBy('position', 4)->getNeighbor('bottom'));
		$this->assertEquals(-1, 													$grid->getContainer()->findBy('position', 4)->getNeighbor('left'));

		/* Box 6 */
		$this->assertEquals($grid->getContainer()->findBy('position', 2)->getId(),	$grid->getContainer()->findBy('position', 6)->getNeighbor('top'));
		$this->assertEquals($grid->getContainer()->findBy('position', 7)->getId(),	$grid->getContainer()->findBy('position', 6)->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 10)->getId(),	$grid->getContainer()->findBy('position', 6)->getNeighbor('bottom'));
		$this->assertEquals($grid->getContainer()->findBy('position', 5)->getId(),	$grid->getContainer()->findBy('position', 6)->getNeighbor('left'));

		/* Box 9 */
		$this->assertEquals($grid->getContainer()->findBy('position', 5)->getId(), 	$grid->getContainer()->findBy('position', 9)->getNeighbor('top'));
		$this->assertEquals($grid->getContainer()->findBy('position', 10)->getId(), $grid->getContainer()->findBy('position', 9)->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 13)->getId(), $grid->getContainer()->findBy('position', 9)->getNeighbor('bottom'));
		$this->assertEquals($grid->getContainer()->findBy('position', 8)->getId(), 	$grid->getContainer()->findBy('position', 9)->getNeighbor('left'));

		/* Box 11 */
		$this->assertEquals($grid->getContainer()->findBy('position', 7)->getId(), 	$grid->getContainer()->findBy('position', 11)->getNeighbor('top'));
		$this->assertEquals(-1, 													$grid->getContainer()->findBy('position', 11)->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 15)->getId(), $grid->getContainer()->findBy('position', 11)->getNeighbor('bottom'));
		$this->assertEquals($grid->getContainer()->findBy('position', 10)->getId(), $grid->getContainer()->findBy('position', 11)->getNeighbor('left'));

		/* Box 12 */
		$this->assertEquals($grid->getContainer()->findBy('position', 8)->getId(), 	$grid->getContainer()->findBy('position', 12)->getNeighbor('top'));
		$this->assertEquals($grid->getContainer()->findBy('position', 13)->getId(), $grid->getContainer()->findBy('position', 12)->getNeighbor('right'));
		$this->assertEquals(-1, 													$grid->getContainer()->findBy('position', 12)->getNeighbor('bottom'));
		$this->assertEquals(-1, 													$grid->getContainer()->findBy('position', 12)->getNeighbor('left'));

		/* Box 15 */
		$this->assertEquals($grid->getContainer()->findBy('position', 11)->getId(), $grid->getContainer()->findBy('position', 15)->getNeighbor('top'));
		$this->assertEquals(-1, 													$grid->getContainer()->findBy('position', 15)->getNeighbor('right'));
		$this->assertEquals(-1, 													$grid->getContainer()->findBy('position', 15)->getNeighbor('bottom'));
		$this->assertEquals($grid->getContainer()->findBy('position', 14)->getId(), $grid->getContainer()->findBy('position', 15)->getNeighbor('left'));
	}

	public function testSwitchBoxesIsPossible()
	{
		$grid = $this->createGrid();

		/* True */
		/* Test 5 and 6 */

		$this->assertTrue(
			($grid->getContainer()->check($grid->getContainer()->findBy('position', 5)->getId(), $grid->getContainer()->findBy('position', 6)->getId()))
			instanceof
			\Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
		);

		/* Test 1 and 0 */
		$this->assertTrue(
			$grid->getContainer()->check($grid->getContainer()->findBy('position', 1)->getId(), $grid->getContainer()->findBy('position', 0)->getId())
			instanceof
			\Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
		);

		/* Test 2 and 6 */
		$this->assertTrue(
			$grid->getContainer()->check($grid->getContainer()->findBy('position', 2)->getId(), $grid->getContainer()->findBy('position', 6)->getId())
			instanceof
			\Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
		);

		/* Test 11 and 10 */
		$this->assertTrue(
			$grid->getContainer()->check($grid->getContainer()->findBy('position', 11)->getId(), $grid->getContainer()->findBy('position', 10)->getId())
			instanceof
			\Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
		);

		/* Test 11 and 15 */
		$this->assertTrue(
			$grid->getContainer()->check($grid->getContainer()->findBy('position', 11)->getId(), $grid->getContainer()->findBy('position', 15)->getId())
			instanceof
			\Spacycrush\Element\Box\BoxContainer\BoxContainerInterface
		);
	}

	/**
	* @expectedException Spacycrush\Exception\NoSwitchPossibleException
	*/
	public function testSwitchBoxesIsNotPossibleWithNoSwitchPossibleException1 ()
	{
		$grid = $this->createGrid();

		/* Test 5 and 0 */
		$grid->getContainer()->check($grid->getContainer()->findBy('position', 5)->getId(), $grid->getContainer()->findBy('position', 0)->getId());
	}

	/**
	* @expectedException Spacycrush\Exception\NoSwitchPossibleException
	*/
	public function testSwitchBoxesIsNotPossibleWithNoSwitchPossibleException2 ()
	{
		$grid = $this->createGrid();

		/* Test 5 and 10 */
		$grid->getContainer()->check($grid->getContainer()->findBy('position', 5)->getId(), $grid->getContainer()->findBy('position', 10)->getId());
	}

	/**
	* @expectedException Spacycrush\Exception\NoSwitchPossibleException
	*/
	public function testSwitchBoxesIsNotPossibleWithNoSwitchPossibleException3 ()
	{
		$grid = $this->createGrid();

		/* Test 0 and 2 */
		$grid->getContainer()->check($grid->getContainer()->findBy('position', 0)->getId(), $grid->getContainer()->findBy('position', 2)->getId());

	}

	/**
	* @expectedException Spacycrush\Exception\NoSwitchPossibleException
	*/
	public function testSwitchBoxesIsNotPossibleWithNoSwitchPossibleException4 ()
	{
		$grid = $this->createGrid();

		/* Test 14 and 11 */
		$this->assertFalse($grid->getContainer()->check($grid->getContainer()->findBy('position', 14)->getId(), $grid->getContainer()->findBy('position', 11)->getId()));
	}

	/**
	* @expectedException Spacycrush\Exception\NoSwitchPossibleException
	*/
	public function testSwitchBoxesIsNotPossibleWithNoSwitchPossibleException5 ()
	{
		$grid = $this->createGrid();

		/* Test 12 and 15 */
		$grid->getContainer()->check($grid->getContainer()->findBy('position', 12)->getId(), $grid->getContainer()->findBy('position', 15)->getId());
	}

	public function testSwitchBoxesIsCorrect()
	{
		$grid = $this->createGrid();

		// Boxes 14 and 15
		$box14 = $grid->getContainer()->findBy('position', 14); $box15 = $grid->getContainer()->findBy('position', 15);
		$savedId = array($box14->getId(), $box15->getId());
		if( $grid->getContainer()->check($box14->getId(), $box15->getId()) ){
			$grid->getContainer()->doSwitch($box14->getId(), $box15->getId());
		}

		/* Confirm the boxes have moved */
		$this->assertEquals($savedId[0], $grid->getContainer()->findBy('position', 15)->getId());
		$this->assertEquals($savedId[1], $grid->getContainer()->findBy('position', 14)->getId());

		/* Tests if selected boxes have correct neighborhoods */
		$newBox14 = $grid->getContainer()->findBy('position', 14); $newBox15 = $grid->getContainer()->findBy('position', 15);
		$this->assertEquals($grid->getContainer()->findBy('position', 13)->getId(), 	$newBox14->getNeighbor('left'));
		$this->assertEquals(-1,									 	$newBox14->getNeighbor('bottom'));
		$this->assertEquals($grid->getContainer()->findBy('position', 15)->getId(), 	$newBox14->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 10)->getId(), 	$newBox14->getNeighbor('top'));

		$this->assertEquals($grid->getContainer()->findBy('position', 14)->getId(), 	$newBox15->getNeighbor('left'));
		$this->assertEquals(-1,									 	$newBox15->getNeighbor('bottom'));
		$this->assertEquals(-1,									 	$newBox15->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 11)->getId(), 	$newBox15->getNeighbor('top'));

		/* Tests if the neighborhoods have the correct selected box as neighborhood */
		$this->assertEquals($grid->getContainer()->findBy('position', 14)->getId(), 	$grid->getContainer()->findBy('position', 13)->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 14)->getId(), 	$grid->getContainer()->findBy('position', 10)->getNeighbor('bottom'));
		$this->assertEquals($grid->getContainer()->findBy('position', 15)->getId(), 	$grid->getContainer()->findBy('position', 11)->getNeighbor('bottom'));





		// Boxes 5 and 6
		$box5 = $grid->getContainer()->findBy('position', 5); $box6 = $grid->getContainer()->findBy('position', 6);
		$savedId = array($box5->getId(), $box6->getId());
		if( $grid->getContainer()->check($box5->getId(), $box6->getId()) ){
			$grid->getContainer()->doSwitch($box5->getId(), $box6->getId());
		}

		$this->assertEquals($savedId[0], $grid->getContainer()->findBy('position', 6)->getId());
		$this->assertEquals($savedId[1], $grid->getContainer()->findBy('position', 5)->getId());


		/* Tests if selected boxes have correct neighborhoods */
		$newBox5 = $grid->getContainer()->findBy('position', 5); $newBox6 = $grid->getContainer()->findBy('position', 6);
		$this->assertEquals($grid->getContainer()->findBy('position', 4)->getId(), 	$newBox5->getNeighbor('left'));
		$this->assertEquals($grid->getContainer()->findBy('position', 9)->getId(), 	$newBox5->getNeighbor('bottom'));
		$this->assertEquals($grid->getContainer()->findBy('position', 6)->getId(), 	$newBox5->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 1)->getId(), 	$newBox5->getNeighbor('top'));

		$this->assertEquals($grid->getContainer()->findBy('position', 5)->getId(), 	$newBox6->getNeighbor('left'));
		$this->assertEquals($grid->getContainer()->findBy('position', 10)->getId(), 	$newBox6->getNeighbor('bottom'));
		$this->assertEquals($grid->getContainer()->findBy('position', 7)->getId(), 	$newBox6->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 2)->getId(), 	$newBox6->getNeighbor('top'));

		/* Tests if the neighborhoods have the correct selected box as neighborhood */
		$this->assertEquals($grid->getContainer()->findBy('position', 5)->getId(), 	$grid->getContainer()->findBy('position', 4)->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 5)->getId(), 	$grid->getContainer()->findBy('position', 9)->getNeighbor('top'));
		$this->assertEquals($grid->getContainer()->findBy('position', 5)->getId(), 	$grid->getContainer()->findBy('position', 1)->getNeighbor('bottom'));
		$this->assertEquals($grid->getContainer()->findBy('position', 6)->getId(), 	$grid->getContainer()->findBy('position', 10)->getNeighbor('top'));
		$this->assertEquals($grid->getContainer()->findBy('position', 6)->getId(), 	$grid->getContainer()->findBy('position', 7)->getNeighbor('left'));
		$this->assertEquals($grid->getContainer()->findBy('position', 6)->getId(), 	$grid->getContainer()->findBy('position', 2)->getNeighbor('bottom'));





		// Boxes 1 and 5
		$box1 = $grid->getContainer()->findBy('position', 1); $box5 = $grid->getContainer()->findBy('position', 5);
		$savedId = array($box1->getId(), $box5->getId());
		if( $grid->getContainer()->check($box1->getId(), $box5->getId()) ){
			$grid->getContainer()->doSwitch($box1->getId(), $box5->getId());
		}

		$this->assertEquals($savedId[0], $grid->getContainer()->findBy('position', 5)->getId());
		$this->assertEquals($savedId[1], $grid->getContainer()->findBy('position', 1)->getId());



		$newBox1 = $grid->getContainer()->findBy('position', 1); $newBox5 = $grid->getContainer()->findBy('position', 5);
		$this->assertEquals($grid->getContainer()->findBy('position', 0)->getId(), 	$newBox1->getNeighbor('left'));
		$this->assertEquals($grid->getContainer()->findBy('position', 5)->getId(), 	$newBox1->getNeighbor('bottom'));
		$this->assertEquals($grid->getContainer()->findBy('position', 2)->getId(), 	$newBox1->getNeighbor('right'));
		$this->assertEquals(-1, 									$newBox1->getNeighbor('top'));

		$this->assertEquals($grid->getContainer()->findBy('position', 4)->getId(), 	$newBox5->getNeighbor('left'));
		$this->assertEquals($grid->getContainer()->findBy('position', 9)->getId(), 	$newBox5->getNeighbor('bottom'));
		$this->assertEquals($grid->getContainer()->findBy('position', 6)->getId(), 	$newBox5->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 1)->getId(), 	$newBox5->getNeighbor('top'));

		//Tests if the neighborhoods have the correct selected box as neighborhood
		$this->assertEquals($grid->getContainer()->findBy('position', 1)->getId(), 	$grid->getContainer()->findBy('position', 0)->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 1)->getId(), 	$grid->getContainer()->findBy('position', 2)->getNeighbor('left'));
		$this->assertEquals($grid->getContainer()->findBy('position', 5)->getId(), 	$grid->getContainer()->findBy('position', 4)->getNeighbor('right'));
		$this->assertEquals($grid->getContainer()->findBy('position', 5)->getId(), 	$grid->getContainer()->findBy('position', 9)->getNeighbor('top'));
		$this->assertEquals($grid->getContainer()->findBy('position', 5)->getId(), 	$grid->getContainer()->findBy('position', 6)->getNeighbor('left'));
	}

	/**
	* @expectedException Spacycrush\Exception\SwitchUselessException
	*/
	public function testSwitchBoxesIsNotCorrectWithSwitchUselessException1 ()
	{
		$grid = $this->createGrid();

		// Boxes 2 and 6
		$box2 = $grid->getContainer()->findBy('position', 2); $box6 = $grid->getContainer()->findBy('position', 6);
		$savedId = array($box2->getId(), $box6->getId());
		if( $whoWhere = $grid->getContainer()->check($box2->getId(), $box6->getId()) ){
			$grid->getContainer()->doSwitch($box2->getId(), $box6->getId(), $whoWhere);
		}
	}

	/**
	* @expectedException Spacycrush\Exception\SwitchUselessException
	*/
	public function testSwitchBoxesIsNotCorrectWithSwitchUselessException2 ()
	{
		$grid = $this->createGrid();

		// Boxes 1 and 0
		$box1 = $grid->getContainer()->findBy('position', 1); $box0 = $grid->getContainer()->findBy('position', 0);
		$savedId = array($box1->getId(), $box0->getId());
		if( $whoWhere = $grid->getContainer()->check($box1->getId(), $box0->getId()) ){
			$grid->getContainer()->doSwitch($box1->getId(), $box0->getId(), $whoWhere);
		}
	}

	public function testBoxesAlignedIsCorrect()
	{
		$grid = $this->createGrid();

		$this->assertTrue($grid->getContainer()->hasAlignment());

		$this->assertEquals([
			$grid->getContainer()->findBy('position', 9)->getId() => $grid->getContainer()->findBy('position', 9),
			$grid->getContainer()->findBy('position', 10)->getId() => $grid->getContainer()->findBy('position', 10),
			$grid->getContainer()->findBy('position', 11)->getId() => $grid->getContainer()->findBy('position', 11),
			$grid->getContainer()->findBy('position', 6)->getId() => $grid->getContainer()->findBy('position', 6),
			$grid->getContainer()->findBy('position', 10)->getId() => $grid->getContainer()->findBy('position', 10),
			$grid->getContainer()->findBy('position', 14)->getId() => $grid->getContainer()->findBy('position', 14),
			$grid->getContainer()->findBy('position', 0)->getId() => $grid->getContainer()->findBy('position', 0),
			$grid->getContainer()->findBy('position', 4)->getId() => $grid->getContainer()->findBy('position', 4),
			$grid->getContainer()->findBy('position', 8)->getId() => $grid->getContainer()->findBy('position', 8),
		], $grid->getContainer()->getAlignedBoxes());

	}

	public function testBoxesAlignedAreRemovedAndGravityDoesItsStuff()
	{
		$grid = $this->createGrid(); $gridCp = clone $grid; $boxesFromGridCp = $gridCp->getContainer()->all();

		$this->assertTrue($grid->getContainer()->hasAlignment());

		$grid->getContainer()->removeAlignedBoxes()->fallDownBoxes();

		$this->assertEquals($boxesFromGridCp[1]->getId(), $grid->getContainer()->findBy('position', 5)->getId()
		);
		$this->assertEquals($boxesFromGridCp[5]->getId(), $grid->getContainer()->findBy('position', 9)->getId());

		$this->assertEquals($boxesFromGridCp[2]->getId(), $grid->getContainer()->findBy('position', 14)->getId());

		$this->assertEquals($boxesFromGridCp[3]->getId(), $grid->getContainer()->findBy('position', 7)->getId());
		$this->assertEquals($boxesFromGridCp[7]->getId(), $grid->getContainer()->findBy('position', 11)->getId());

		$this->assertEquals($boxesFromGridCp[9]->getNeighbor('bottom'),	$grid->getContainer()->findBy('position', 13)->getId());
		$this->assertEquals($boxesFromGridCp[9]->getNeighbor('top'), 	$grid->getContainer()->findBy('position', 9)->getId());
		$this->assertEquals($boxesFromGridCp[11]->getNeighbor('top'), 	$grid->getContainer()->findBy('position', 11)->getId());
		$this->assertEquals($boxesFromGridCp[6]->getNeighbor('top'), 	$grid->getContainer()->findBy('position', 14)->getId());

		$this->assertEquals('empty', 									$grid->getContainer()->all()[0]);
		$this->assertEquals('empty', 									$grid->getContainer()->all()[4]);
		$this->assertEquals('empty', 									$grid->getContainer()->all()[6]);
		$this->assertEquals('empty', 									$grid->getContainer()->all()[3]);

	}

	public function testScoreIsCorrect()
	{
		$grid = $this->createGrid();

		$grid->getContainer()->hasAlignment();

		$grid->getContainer()->removeAlignedBoxes()->fallDownBoxes()->calculateScore();

		$this->assertEquals(400, $grid->getContainer()->getScore());



	}

}
