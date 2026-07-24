<?php
namespace FlavorHub\BusinessLogic;

use FlavorHub\DataAccess\CommentDAO;
use Exception;

/**
 * Comment Service (Business Logic Layer)
 * Manages comment moderation approvals and deletions.
 */
class CommentService {
    private CommentDAO $commentDAO;

    public function __construct(CommentDAO $commentDAO) {
        $this->commentDAO = $commentDAO;
    }

    /**
     * Approve a pending comment.
     */
    public function approveComment(int $id): bool {
        $comment = $this->commentDAO->findById($id);
        if (!$comment) {
            throw new Exception("Comment not found.");
        }
        
        return $this->commentDAO->updateStatus($id, 'approved');
    }

    /**
     * Delete a comment.
     */
    public function deleteComment(int $id): bool {
        return $this->commentDAO->delete($id);
    }

    /**
     * Get all comments.
     */
    public function getAllComments(): array {
        return $this->commentDAO->getAll();
    }

    /**
     * Get recent comments.
     */
    public function getRecentComments(int $limit): array {
        return $this->commentDAO->getRecent($limit);
    }

    /**
     * Get total comment count.
     */
    public function getCommentCount(): int {
        return $this->commentDAO->countAll();
    }

    /**
     * Get pending comment count.
     */
    public function getPendingCommentCount(): int {
        return $this->commentDAO->countPending();
    }
}
