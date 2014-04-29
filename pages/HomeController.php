<?php


use Blog\Controller;

	Class HomeController extends Controller
	{
		public function renderArticles()
		{
			$this->data['user'] = $this->isLogged();

			$article = new Article($this->app);

			$this->data['articles'] = $article->getAllArticles();

			return $this->app['twig']->render('home.twig', $this->data);
		}

		public function tagSearch($tagId)
		{
			$this->data['user'] = $this->isLogged();

			$article = new Article($this->app);

			$this->data['tagSearch'] = $article->tagSearch($tagId);

			$tag = new Tag($this->app);

			for ($i=0; $i < count($this->data['tagSearch']); $i++) {
				$this->data['tagSearch'][$i]['tags']= $tag->getTagsArticle($this->data['tagSearch'][$i]['articlesId']);
			}

			return $this->app['twig']->render('tagSearch.twig', $this->data);
		}

		public function renderArticleById($articleId = null)
		{
			$this->data['user'] = $this->isLogged();

			$article = new Article($this->app);

			$this->data['article'] = $article->getArticle($articleId);

			$tag = new Tag($this->app);
			$tags = $tag->getTagsArticle($articleId);

			$this->data['tags'] = $tags;

			$comment = new Comment($this->app);

			$comments = $comment->getComments($articleId);

			$this->data['comments'] = $comments;

			return $this->app['twig']->render('article.twig', $this->data);
		}
		
		public function postComment($articleId = null)
		{
			$this->data['user'] = $this->isLogged();

			if( $this->isLogged() ){
				// requete d'ajout de commentaire
				if ($articleId) {
					$comment = new Comment($this->app);
					$comment->saveComment( $articleId, $this->app['request']->get('comment'), $this->data['user']['id'] );
				}
			}

			return $this->redirect('renderArticleById', array('articleId' => $articleId));
		}

		public function getCommentList()
		{
			if(!$this->isAdmin()){
				return $this->redirect('home');
			}

			$comment = new Comment($this->app);

			$this->data['comments'] = $comment->getAllComments();

			return $this->app['twig']->render('commentList.twig', $this->data);
		}

		public function deleteComment($value='')
		{
			if(!$this->isAdmin()){
				return $this->redirect('home');
			}

			$comment = new Comment($this->app);

			$comment->removeComment($this->app['request']->get('selectedComments'));

			return $this->redirect('getCommentList');
		}
		// puis la présentation à préparer
	}
		