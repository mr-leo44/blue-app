<?php
echo "<ul class=\"pagination\">";
 
// button for first page
 $href=$page <= 1?'#':$page_url.'page=1';
 $disabled=$page <= 1?'disabled':'';
    echo '<li class="page-item '. $disabled.'" ><a href="'. $href.'" class="page-link"><i class="fa fa-angle-double-left"></i></a></li>'; 
// count all products in the database to calculate total pages
$total_pages = ceil($total_rows / $records_per_page);
	$half = ceil( $range / 2 );
	
$numbers['start']=0;
		$numbers['end']=0;
// Prev + Next
$prev = $page - 1;
$next = $page + 1;
// display links to 'range of pages' around 'current page'
$initial_num = $page - $range;
$condition_limit_num = ($page + $range)  + 1;

    for ($x=$initial_num; $x<$condition_limit_num; $x++) 
    {

        // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
        if (($x > 0) && ($x <= $total_pages)) 
        {

            // current page
            if ($x == $page) 
            {
              //  echo "<span class='customBtn' style='background:#0D6598;'>$x</span>";
				echo "<li class='page-item active'><a class='page-link' href=\"#\">$x</a></li>";
            } 

            // not current page
            else 
            {
               // echo " <a href='{$_SERVER['PHP_SELF']}?page=$x' class='customBtn'>$x</a> ";
			    //echo "<li class='page-item' ><a class='page-link' href='{$page_url}page=$x'>$x</a></li>";
			    echo "<li class='page-item' ><a class='page-link' href='{$page_url}page=$x'>$x</a></li>";
            }
        }
    }

$href=$page == $total_pages ? '#':$page_url.'page='.$total_pages;
$disabled=$page == $total_pages ? 'disabled':'';

 echo '<li class="page-item '. $disabled.'" ><a href="'.$href .'" class="page-link"><i class="fa fa-angle-double-right"></i></a></li>'; 
 
echo "</ul>";

function _range( $len, $start )
	{
		$out = [];
		$end=0;
	
		if ( $start <= 1 ) {
			$start = 0;
			$end = $len;
		}
		else {
			$end = $start;
			$start = $len;
		}
		$out['start']=$start;
		$out['end']=$end;
		return $out;
	}
/*
var _range = function ( len, start )
	{
		var out = [];
		var end;
	
		if ( start === undefined ) {
			start = 0;
			end = len;
		}
		else {
			end = start;
			start = len;
		}
	
		for ( var i=start ; i<end ; i++ ) {
			out.push( i );
		}
	
		return out;
	};
	
function _numbers ( page, pages ) {
		var
			numbers = [],
			buttons = extPagination.numbers_length,
			half = Math.floor( buttons / 2 ),
			i = 1;
	
		if ( pages <= buttons ) {
			numbers = _range( 0, pages );
		}
		else if ( page <= half ) {
			numbers = _range( 0, buttons-2 );
			numbers.push( 'ellipsis' );
			numbers.push( pages-1 );
		}
		else if ( page >= pages - 1 - half ) {
			numbers = _range( pages-(buttons-2), pages );
			numbers.splice( 0, 0, 'ellipsis' ); // no unshift in ie6
			numbers.splice( 0, 0, 0 );
		}
		else {
			numbers = _range( page-half+2, page+half-1 );
			numbers.push( 'ellipsis' );
			numbers.push( pages-1 );
			numbers.splice( 0, 0, 'ellipsis' );
			numbers.splice( 0, 0, 0 );
		}
	
		numbers.DT_el = 'span';
		return numbers;
	}
	
	
	var attach = function( container, buttons ) {
					var i, ien, node, button;
					var clickHandler = function ( e ) {
						_fnPageChange( settings, e.data.action, true );
					};
	
					for ( i=0, ien=buttons.length ; i<ien ; i++ ) {
						button = buttons[i];
	
						if ( $.isArray( button ) ) {
							var inner = $( '<'+(button.DT_el || 'div')+'/>' )
								.appendTo( container );
							attach( inner, button );
						}
						else {
							btnDisplay = null;
							btnClass = '';
	
							switch ( button ) {
								case 'ellipsis':
									container.append('<span class="ellipsis">&#x2026;</span>');
									break;
	
								case 'first':
									btnDisplay = lang.sFirst;
									btnClass = button + (page > 0 ?
										'' : ' '+classes.sPageButtonDisabled);
									break;
	
								case 'previous':
									btnDisplay = lang.sPrevious;
									btnClass = button + (page > 0 ?
										'' : ' '+classes.sPageButtonDisabled);
									break;
	
								case 'next':
									btnDisplay = lang.sNext;
									btnClass = button + (page < pages-1 ?
										'' : ' '+classes.sPageButtonDisabled);
									break;
	
								case 'last':
									btnDisplay = lang.sLast;
									btnClass = button + (page < pages-1 ?
										'' : ' '+classes.sPageButtonDisabled);
									break;
	
								default:
									btnDisplay = button + 1;
									btnClass = page === button ?
										classes.sPageButtonActive : '';
									break;
							}
	
							if ( btnDisplay !== null ) {
								node = $('<a>', {
										'class': classes.sPageButton+' '+btnClass,
										'aria-controls': settings.sTableId,
										'aria-label': aria[ button ],
										'data-dt-idx': counter,
										'tabindex': settings.iTabIndex,
										'id': idx === 0 && typeof button === 'string' ?
											settings.sTableId +'_'+ button :
											null
									} )
									.html( btnDisplay )
									.appendTo( container );
	
								_fnBindAction(
									node, {action: button}, clickHandler
								);
	
								counter++;
							}
						}
					}
				};
	
	
*/
?>



