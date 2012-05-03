<?php
/*
 *  The MIT License
 *
 *  Copyright (c) 2010 Johannes Mueller <circus2(at)web.de>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in
 *  all copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

namespace MwbExporter\Formatter\Zend\DbTable\Model;

use MwbExporter\Core\Registry;
use MwbExporter\Core\Model\ForeignKey as Base;

class ForeignKey extends Base
{
    /**
     *
     * @param SimpleXMLElement $data
     * @param type $parent
     */
    public function __construct($data, $parent)
    {
        parent::__construct($data, $parent);

        $referencedColumn = $this->data->xpath("value[@key='referencedColumns']");
        $local = Registry::get((string) $referencedColumn[0]->link);

        $ownerColumn = $this->data->xpath("value[@key='columns']");
        $foreign = Registry::get((string) $ownerColumn[0]->link);

        $this->local   = $local;   // local column object
        $this->foreign = $foreign; // foreign column object

        // for doctrine2 annotations switch the local and the foreign
        // reference for a proper output
        $local->markAsForeignReference($this);
        $foreign->markAsLocalReference($this);

        // many to many
        if($fk = $this->getOwningTable()->getForeignKeys()){
            // only two or more foreign keys implicate an m2m relation
            // of the current table
            if(count($fk) > 1){
                foreach($fk as $foreignKey1){
                    foreach($fk as $foreignKey2){
                        if($foreignKey1->getReferencedTable()->getId() != $foreignKey2->getReferencedTable()->getId()){
                            $foreignKey1->getReferencedTable()->setManyToManyRelation(
                                array(
                                    'reference'  => $this,
                                    'refTable'   => $foreignKey2->getReferencedTable()
                                )
                            );
                        }
                    }
                }
            }
        }
    }

    /**
     *
     * @return string
     */
    public function display()
    {
        $return = array();

<<<<<<< HEAD
        $return[] = $this->indentation(2) .'\''. $this->getReferencedTable()->getRawTableName() .'\' => array(';
        $return[] = $this->indentation(3) .'\'columns\'       => \''. $this->foreign->getColumnName() .'\',';
        $return[] = $this->indentation(3) .'\'refTableClass\' => \''. $this->getReferencedTable()->getRawTableName() .'\',';
        $return[] = $this->indentation(3) .'\'refColumns\'    => \''. $this->local->getColumnName() .'\',';
        $return[] = $this->indentation(2) .'),';
=======
        $return[] = $this->indentation(3) .'\''. $this->getReferencedTable()->getRawTableName() .'\' => array(';
        $return[] = $this->indentation(4) .'\'columns\'       => \''. $this->foreign->getColumnName() .'\',';
        $return[] = $this->indentation(4) .'\'refTableClass\' => \''. $this->getReferencedTable()->getTableClassName() .'\',';
        $return[] = $this->indentation(4) .'\'refColumns\'    => \''. $this->local->getColumnName() .'\',';
        $return[] = $this->indentation(3) .'),';
>>>>>>> Improved Sencha3Model output

        return implode("\n", $return);
    }
}