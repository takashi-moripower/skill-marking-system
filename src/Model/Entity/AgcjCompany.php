<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Hash;
use Cake\ORM\TableRegistry;

class AgcjCompany extends Entity {

    protected function _getAddress($var) {
        if (isset($var)) {
            return $var;
        }

        $address1 = Hash::get(Hash::extract($this, 'options.{n}[meta_key=住所１].meta_value'), 0, '');
        $address2 = Hash::get(Hash::extract($this, 'options.{n}[meta_key=住所２].meta_value'), 0, '');

        $address = implode(' ', [$address1, $address2]);
        $this->address = $address;
        return $address;
    }

    protected function _getUrl($var) {
        return $this->_getOption('URL', 'url', $var);
    }

    protected function _getLogo($var) {
        if (isset($var)) {
            return $var;
        }

        $postId = Hash::get(Hash::extract($this, 'options.{n}[meta_key=企業ロゴ].meta_value'), 0, '');

        $Table = TableRegistry::get('AgcjCompanies');

        $post = $Table->find()
                ->where(['ID' => $postId])
                ->select('guid')
                ->first();

        if (empty($post->guid)) {
            return null;
        }

        return $post->guid;
    }

    protected function _getOption($key, $label, $var) {
        if (isset($var)) {
            return $var;
        }

        $newVar = Hash::get(Hash::extract($this, 'options.{n}[meta_key=' . $key . '].meta_value'), 0, '');

        $this->{$label} = $newVar;
        return $newVar;
    }

    protected function _getArea($var) {
        return $this->_getTermsByTaxnomy('area', $var);
    }

    protected function _getBusiness($var) {
        return $this->_getTermsByTaxnomy('business', $var);
    }

    protected function _getCgTools($var) {
        return $this->_getTermsByTaxnomy('cg_tool', $var);
    }

    protected function _getPlatforms($var) {
        return $this->_getTermsByTaxnomy('platform', $var);
    }

    protected function _getTermsByTaxnomy($taxonomy, $var) {
        if (isset($var)) {
            return $var;
        }

        $terms = Hash::filter(Hash::extract($this, 'terms'), function($term) use($taxonomy) {
                    return (isset($term->taxonomy->taxonomy) && $term->taxonomy->taxonomy == $taxonomy);
                });

        return Hash::extract($terms, '{n}.name');
    }

}
