<?php

class metro
{	
	private $graph;
	
	function __construct()
	{
		$this->graph = include( 'graph.php' );
	}	
	
	public function isPathExists( $from, $to )
	{
		$checked = [];
		$queue = [];
		
		if( !isset( $this->graph[ $from ] ) )
		{
			return false;
		}
		$queue[] = $this->graph[ $from ];
		
		while( sizeof( $queue ) > 0 )
		{
			$node = array_pop( $queue );
			 
			foreach( $node as $station )
			{
				if( !array_key_exists( $station, $checked ) )
				{
					array_unshift( $queue, $this->graph[ $station ] );
				}
				$checked[ $station ] = true;
				if( $station == $to )
				{
					return true;
				}
			}
		}
		return false;
	}
	
	public function getPath( $from, $to )
	{
		$checked = [];
		$queue = [];
				
		$queue[] = $this->graph[ $from ];
		
		$path = [];
		foreach( $this->graph[ $from ] as $nextStation )
		{
			$path[ $nextStation ] = [ $from ];
		}
	
		while( sizeof( $queue ) > 0 )
		{
			$node = array_pop( $queue );
						
			foreach( $node as $station )
			{
				if( !array_key_exists( $station, $checked ) )
				{
					array_unshift( $queue, $this->graph[ $station ] );
					foreach( $path as $prevStation => $subPath )
					{
						if( $prevStation == $station AND $station != $from AND $station != $to )
						{
							foreach(  $this->graph[ $station ] as $nextStation ) 
							{
								$path[ $nextStation ] = array_merge( [ $prevStation ], $subPath );
							}
							unset( $path[ $prevStation ] );
						}
					}
				}
				$checked[ $station ] = true;
				if( $station == $to )
				{
					$path = $path[ $to ];
					array_unshift( $path, $to );
					return array_reverse( $path );
				}
			}
		}
		return [];
	}
}

// $metro = new metro();

// var_dump( $metro->isPathExists( 'Купчино', 'Автово' ) );
// var_dump( $metro->getPath( 'Купчино', 'Автово' ) );
// var_dump( $metro->isPathExists( 'Купчино', 'Станция метро 3/4' ) );
// var_dump( $metro->getPath( 'Купчино', 'Станция метро 3/4' ) );