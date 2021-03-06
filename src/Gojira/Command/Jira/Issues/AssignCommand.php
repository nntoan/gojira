<?php
/**
 * Copyright © 2017 Toan Nguyen. All rights reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gojira\Command\Jira\Issues;

use Gojira\Api\Request\StatusCodes;
use Gojira\Command\Jira\AbstractCommand;
use Gojira\Jira\Endpoint\IssueEndpoint;
use Gojira\Jira\Response\IssueResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Assign an issue to <user>. Provide only issue# to assign to me (issue:assign)
 *
 * @package Gojira\Command\Jira\Issues
 * @author  Toan Nguyen <me@nntoan.com>
 */
class AssignCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $help = __(
            "Assign Issue Help:\n%1\n%2",
            ' <issue>: JIRA issue want to assign',
            ' <user>: (optional) User assigned to'
        );

        $this
            ->setName('issue:assign')
            ->setAliases(['assign'])
            ->setDescription('Assign an issue to <user>. Provide only issue# to assign to me')
            ->setHelp($help)
            ->addArgument(IssueEndpoint::ENDPOINT, InputArgument::REQUIRED, 'JIRA issue to log work for')
            ->addArgument(IssueEndpoint::EP_ASSIGNEE, InputArgument::OPTIONAL, 'How much time spent');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->authentication->isAuth()) {
            $issue = $input->getArgument(IssueEndpoint::ENDPOINT);
            $assignee = $input->getArgument(IssueEndpoint::EP_ASSIGNEE) ?: $this->authentication->getUsername();

            $this->doExecute(
                $output,
                StatusCodes::HTTP_NO_CONTENT,
                [IssueEndpoint::ENDPOINT => $issue, IssueEndpoint::EP_ASSIGNEE => $assignee],
                [],
                __('<info>Issue [%1] assigned to </info> <comment>%2</comment>.', $issue, $assignee)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getResponse($filters = [])
    {
        $issueEndpoint = new IssueEndpoint($this->getApiClient());
        return $issueEndpoint->assign(
            $filters[IssueEndpoint::ENDPOINT],
            $filters[IssueEndpoint::EP_ASSIGNEE]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function renderResult($response = [], $type = null)
    {
        return (new IssueResponse($response))->render($type);
    }
}
