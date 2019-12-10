<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="attachments")
 * @Vich\Uploadable
 */
class Attachment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     *
     */
    protected $nodeId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $fileName;

    /**
     * @Assert\Image(mimeTypes={"image/*"})
     * @Vich\UploadableField(mapping="node_attachments", fileNameProperty="fileName")
     * @var File
     */
    private $attachmentFile;

    /**
     *  @ORM\ManyToOne(targetEntity="Node", inversedBy="attachments", fetch="EAGER")
     *  @ORM\JoinColumn(name="node_id", referencedColumnName="id")
     */
    protected $node;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime|null
     */
    private $uploadedAt;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param mixed $node
     */
    public function setNode($node): void
    {
        $this->node = $node;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param mixed $file_name
     */
    public function setFileName($file_name): void
    {
        $this->fileName = $file_name;
    }

    public function setAttachmentFile(File $attachment = null)
    {
        $this->attachmentFile = $attachment;
        if ($attachment instanceof UploadedFile) {
            $this->uploadedAt = new \DateTime('now');
        }
    }

    public function getAttachmentFile()
    {
        return $this->attachmentFile;
    }

    public function getUploadedAt()
    {
        return $this->uploadedAt;
    }
}
