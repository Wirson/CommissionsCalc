<?php
/**
 * @copyright Copyright (c) Mateusz Wira (mwira@gmail.com)
 */

declare(strict_types=1);

namespace App\Console;

#[\Symfony\Component\Console\Attribute\AsCommand(
    name: 'app:calculate',
    description: 'Calculate commissions rates'
)]
class CalculateCommissions extends \Symfony\Component\Console\Command\Command
{
    private const ARG_FILENAME = 'filename';

    public function __construct(
        private readonly \App\Model\FileReader $fileReader,
        private readonly \App\Model\CommissionCalculator $commissionCalculator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:calculate')
            ->setDescription('Calculate commissions rates')
            ->addArgument(
                self::ARG_FILENAME,
                \Symfony\Component\Console\Input\InputArgument::REQUIRED,
                'Input data filename'
            );
    }
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ): int {
        $serializedTransactionsData = $this->fileReader->readFile($input->getArgument(self::ARG_FILENAME));
        foreach ($serializedTransactionsData as $serializedTransaction) {
            $transaction = json_decode($serializedTransaction, true);
            try {
                $output->writeln($this->commissionCalculator->calculate($transaction));
            } catch (\Exception $exception) {
                $output->writeln($exception->getMessage());
            }
        }

        return self::SUCCESS;
    }
}
