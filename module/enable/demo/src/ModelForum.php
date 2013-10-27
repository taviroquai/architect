<?php

namespace Arch\Demo;

/**
 * Model Forum
 */
class ModelForum
{
    
    public function __construct()
    {
        
    }

    public function addTopic($data)
    {
        $topic = $data;
        $topic['alias'] = app()->createSlug($data['title']);
        $topic['id_user'] = 1;
        $topic['datetime'] = date('Y-m-d H:i:s');
        unset($topic['body']);
        unset($topic['topic']);
        q('demo_topic')->i($topic)->run();
        $data['id_topic'] = q('demo_topic')->id();
        $this->addPost($data);
        return $data['id_topic'];
    }
    
    public function addPost($data)
    {
        $post = array();
        $post['datetime'] = date('Y-m-d H:i:s');
        $post['id_user'] = 1;
        $post['id_topic'] = $data['id_topic'];
        $post['body'] = $data['body'];
        q('demo_post')->i($post)->run();
        return q('demo_post')->id();
    }
    
    public function getForum($id)
    {
        return q('demo_forum')
            ->s()
            ->w('id = ?', array($id))
            ->run()
            ->fetchObject();
    }
    
    public function getTopic($id)
    {
        return q('demo_topic')
            ->s()
            ->w('id = ?', array($id))
            ->run()
            ->fetchObject();
    }

        public function getCategories()
    {
        return q('demo_forum')
            ->s('demo_forum.*, count(id_forum) as total_topics')
            ->j('demo_topic', 'demo_forum.id = demo_topic.id_forum')
            ->g('demo_forum.id')
            ->run()
            ->fetchAll(\PDO::FETCH_CLASS);
    }
    
    public function getTopics($id_forum)
    {
        return q('demo_topic')
            ->s('demo_topic.*, count(id_topic) as total_posts')
            ->j('demo_post', 'demo_topic.id = demo_post.id_topic')
            ->w('demo_topic.id_forum = ?', array($id_forum))
            ->g('demo_topic.id')
            ->run()
            ->fetchAll(\PDO::FETCH_CLASS);
    }
    
    public function getPosts($id_topic)
    {
        return q('demo_post')
            ->s()
            ->w('demo_post.id_topic = ?', array($id_topic))
            ->run()->fetchAll(\PDO::FETCH_CLASS);
    }
}