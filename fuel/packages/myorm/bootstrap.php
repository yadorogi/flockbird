<?php
Autoloader::add_classes(array(
	'MyOrm\\Model' => __DIR__.'/classes/model.php',
	'MyOrm\\Observer_CopyValue' => __DIR__.'/classes/observer/copyvalue.php',
	'MyOrm\\Observer_UpdatedAt' => __DIR__.'/classes/observer/updatedat.php',
	'MyOrm\\Observer_SortDatetime' => __DIR__.'/classes/observer/sortdatetime.php',
	//'MyOrm\\Observer_ConvertGeometryData' => __DIR__.'/classes/observer/convertgeometrydata.php',
	'MyOrm\\Observer_CreatedAtCopyFromRelationalTable' => __DIR__.'/classes/observer/createdatcopyfromrelationaltable.php',
	'MyOrm\\Observer_UpdatedAtCopyFromRelationalTable' => __DIR__.'/classes/observer/updatedatcopyfromrelationaltable.php',
	//'MyOrm\\Observer_InsertCache' => __DIR__.'/classes/observer/insertcache.php',
	//'MyOrm\\Observer_InsertCacheDuplicate' => __DIR__.'/classes/observer/insertcacheduplicate.php',
	//'MyOrm\\Observer_UpdateCacheDuplicate' => __DIR__.'/classes/observer/updatecacheduplicate.php',
	'MyOrm\\Observer_InsertRelationialTable' => __DIR__.'/classes/observer/insertrelationaltable.php',
	'MyOrm\\Observer_UpdateRelationalTables' => __DIR__.'/classes/observer/updaterelationaltables.php',
	'MyOrm\\Observer_DeleteRelationalTablesOnUpdated' => __DIR__.'/classes/observer/deleterelationaltablesonupdated.php',
	'MyOrm\\Observer_DeleteRelationalTables' => __DIR__.'/classes/observer/deleterelationaltables.php',
	'MyOrm\\Observer_ExecuteOnCreate' => __DIR__.'/classes/observer/executeoncreate.php',
	'MyOrm\\Observer_ExecuteOnUpdate' => __DIR__.'/classes/observer/executeonupdate.php',
	'MyOrm\\Observer_ExecuteToRelations' => __DIR__.'/classes/observer/executetorelations.php',
	'MyOrm\\Observer_CountUpToRelations' => __DIR__.'/classes/observer/countuptorelations.php',
	'MyOrm\\Observer_CountDownToRelations' => __DIR__.'/classes/observer/countdowntorelations.php',
	'MyOrm\\Observer_RemoveFile' => __DIR__.'/classes/observer/removefile.php',
	'MyOrm\\Observer_InsertFileBinDeleteQueue' => __DIR__.'/classes/observer/insertfilebindeletequeue.php',
	'MyOrm\\Observer_AddMemberFilesizeTotal' => __DIR__.'/classes/observer/addmemberfilesizetotal.php',
	'MyOrm\\Observer_SubtractMemberFilesizeTotal' => __DIR__.'/classes/observer/subtractmemberfilesizetotal.php',
	'MyOrm\\Observer_DeleteMember' => __DIR__.'/classes/observer/deletemember.php',
	'MyOrm\\Observer_UpdateMemberProfileCache' => __DIR__.'/classes/observer/updatememberprofilecache.php',
	'MyOrm\\Observer_UpdateMemberRelationByFollow' => __DIR__.'/classes/observer/updatememberrelationbyfollow.php',
	'MyOrm\\Observer_UpdateProfile' => __DIR__.'/classes/observer/updateprofile.php',
	'MyOrm\\Observer_DeleteAlbum' => __DIR__.'/classes/observer/deletealbum.php',
	'MyOrm\\Observer_DeleteAlbumImage' => __DIR__.'/classes/observer/deletealbumimage.php',
	'MyOrm\\Observer_SaveAlbumImageLocation' => __DIR__.'/classes/observer/savealbumimagelocation.php',
	'MyOrm\\Observer_InsertTimelineCache' => __DIR__.'/classes/observer/inserttimelinecache.php',
	'MyOrm\\Observer_UpdateTimelineCache' => __DIR__.'/classes/observer/updatetimelinecache.php',
	'MyOrm\\Observer_UpdateTimeline' => __DIR__.'/classes/observer/updatetimeline.php',
	'MyOrm\\Observer_UpdateTimelineImportanceLevel' => __DIR__.'/classes/observer/updatetimelineimportancelevel.php',
	'MyOrm\\Observer_DeleteTimeline' => __DIR__.'/classes/observer/deletetimeline.php',
	'MyOrm\\Observer_UpdateTimeline4ChildData' => __DIR__.'/classes/observer/updatetimeline4childdata.php',
	'MyOrm\\Observer_MemberWatchContentInserted' => __DIR__.'/classes/observer/memberwatchcontentinserted.php',
	//'MyOrm\\Observer_MemberWatchContentDeleted' => __DIR__.'/classes/observer/memberwatchcontentdeleted.php',
	'MyOrm\\Observer_InsertMemberFollowTimeline' => __DIR__.'/classes/observer/insertmemberfollowtimeline.php',
	'MyOrm\\Observer_InsertNotice' => __DIR__.'/classes/observer/insernotice.php',
	'MyOrm\\Observer_DeleteNotice' => __DIR__.'/classes/observer/deletenotice.php',
	'MyOrm\\Observer_DeleteUnreadNoticeCountCache' => __DIR__.'/classes/observer/deleteunreadnoticecountcache.php',
));

/* End of file bootstrap.php */
