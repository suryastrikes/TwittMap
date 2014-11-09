<?php 
	
	require '/Users/Surya/vendor/autoload.php';
	use Aws\Common\Aws;
	use Aws\DynamoDb\DynamoDbClient;

	function getDynamoClient()
	{
		$aws = Aws::factory('config.php');
		$client = $aws->get('DynamoDb');
		/*$client = DynamoDbClient::factory(array(
    	'profile' => 'default',
    	'region'  => 'us-east-1'
		));*/
		
		
		return $client;
	}
	
	function getAllKeywords()
	{
		$client = getDynamoClient();
	
		$iterator =  $client->scan(array(
    	'TableName' => 'keywordTweetIdMap',
		));
		
		
		
		$cnt2 = 0;
		foreach ($iterator as $object) {
		
			//print_r($object);
			if(is_array($object))
			{
				foreach($object as $item)
				{
					//print_r($item);
					if(!is_null($item) && !is_null($item['keyword']) )
					{
						$allKeywordsList[$cnt2]['keyword']=$item['keyword']['S'];
						$allKeywordsList[$cnt2]['noOfTweets']=count($item['tweetIdSet']['NS']);
						$cnt2++;
					}
					
				}
			}
		}
		//echo "Size: " . array_count_values($allKeywordsList[0]['keyword'])."\n";
		//print_r($allKeywordsList);
		return $allKeywordsList;	
		
	}
	
	
	function getAllTweets()
	{
		$client = getDynamoClient();
	
		$iterator =  $client->scan(array(
    	'TableName' => 'TweetData',
		));
		
		//print_r($iterator);
		
		$cnt1 = 0;
		foreach ($iterator as $object) {
			if(is_array($object))
			{
				
				foreach($object as $item)
				{
					
					if(!is_null($item) && !is_null($item['tweetId']) )
					{
						$alltweetList[$cnt1]['tweetId'] = $item['tweetId'][N];
						$alltweetList[$cnt1]['name'] = $item['name'][S];
						$alltweetList[$cnt1]['location'] = $item['location'][S];
						$alltweetList[$cnt1]['tweet'] = $item['tweet'][S];
						$cnt1++;
					}
					
				}
			}
		}
		//print_r($tweetList);
		return $alltweetList;	
		
	}
	
	
	
	function getTweetsForKeyword($keyword)
	{
		$client = getDynamoClient();
	
		$iterator =  $client->query(array(
    	'TableName'     => 'keywordTweetIdMap',
    	'KeyConditions' => array(
        					'keyword' => array(
            				'AttributeValueList' => array(
                					array('S' => $keyword)
            						),
            				'ComparisonOperator' => 'EQ'
        				  	)
    					)
		));
	
	
		$count  = 0;
		foreach ($iterator as $object) {
	
			if($count == 1)
			{
    			$obj = $object[0];
    			//print_r($obj['tweetIdSet']);
    			//print_r($obj['tweetIdSet']['NS']);
    			$cnt = 0;	
    			foreach($obj['tweetIdSet']['NS'] as $itr)
    			{
    				if(trim($itr) != "")
    				{
    					$tweetIdList[$cnt] = $itr;
    					$cnt=$cnt+1;
    					
    				}
    			}
    		}
    		$count++;
		}
		
		$cnt1=0;
		foreach($tweetIdList as $id)
		{
			//echo $id."\n";
			$iterator =  $client->query(array(
    			'TableName'     => 'TweetData',
    			'KeyConditions' => array(
        					'tweetId' => array(
            				'AttributeValueList' => array(
                					array('N' => $id)
            						),
            				'ComparisonOperator' => 'EQ'
        				  	)
    					)
			));
			
			
			
			foreach ($iterator as $object) {
				
				if(!is_null($object) && !is_null($object[0]['tweetId']))
				{
					$tweetList[$cnt1]['tweetId'] = $object[0]['tweetId']['N'];
					$tweetList[$cnt1]['name'] = $object[0]['name']['S'];
					$tweetList[$cnt1]['location'] = $object[0]['location']['S'];
					$tweetList[$cnt1]['tweet'] = $object[0]['tweet']['S'];
					$cnt1++;
				}
			
			}
	
		}
		
		//print_r($tweetList);
		return $tweetList;	
		
	}
	
	//$tweetList = getTweetsForKeyword('http');
	//$tweetList= getAllTweets();
 	//$tweetList = getAllKeywords();
	//print_r($tweetList);
?> 
